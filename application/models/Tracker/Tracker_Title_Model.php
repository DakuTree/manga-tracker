<?php declare(strict_types=1); defined('BASEPATH') OR exit('No direct script access allowed');

class Tracker_Title_Model extends Tracker_Base_Model {
	public function __construct() {
		parent::__construct();
	}

	/**
	 * @param string $titleURL
	 * @param int    $siteID
	 * @param bool   $create
	 * @param bool   $returnData
	 *
	 * @return array|int
	 */
	public function getID(string $titleURL, int $siteID, bool $create = TRUE, bool $returnData = FALSE) {
		$query = $this->db->select('tracker_titles.id, tracker_titles.title, tracker_titles.title_url, tracker_titles.latest_chapter, tracker_titles.status, tracker_sites.site_class, (tracker_titles.last_checked > DATE_SUB(NOW(), INTERVAL 3 DAY)) AS active', FALSE)
		                  ->from('tracker_titles')
		                  ->join('tracker_sites', 'tracker_sites.id = tracker_titles.site_id', 'left')
		                  ->where('tracker_titles.title_url', $titleURL)
		                  ->where('tracker_titles.site_id', $siteID)
		                  ->get();

		if($query->num_rows() > 0) {
			$id = (int) $query->row('id');

			//This updates inactive series if they are newly added, as noted in https://github.com/DakuTree/manga-tracker/issues/5#issuecomment-247480804
			if(((int) $query->row('active')) === 0 && $query->row('status') === 0) {
				$siteClass = $query->row('site_class');
				$titleData = $this->sites->{$siteClass}->getTitleData($query->row('title_url'));
				if(!is_null($titleData['latest_chapter']) || $this->sites->{$siteClass}->canHaveNoChapters) {
					if($this->updateByID((int) $id, $titleData['latest_chapter'])) {
						//Make sure last_checked is always updated on successful run.
						//CHECK: Is there a reason we aren't just doing this in updateTitleById?
						$this->db->set('last_checked', 'CURRENT_TIMESTAMP', FALSE)
						         ->where('id', $id)
						         ->update('tracker_titles');
					}
				} else {
					log_message('error', "{$siteClass} | {$query->row('title')} ({$query->row('title_url')}) | Failed to update.");
				}
			}

			$titleID = $id;
		} else {
			//TODO: Check if title is valid URL!
			if($create) $titleID = $this->addTitle($titleURL, $siteID);
		}
		if(!isset($titleID) || !$titleID) $titleID = 0;

		return ($returnData && $titleID !== 0 ? $query->row_array() : $titleID);
	}

	/**
	 * @param string $title
	 * @param string $siteURL
	 *
	 * @return array|int
	 * @throws Exception
	 */
	public function getIDFromData(string $title, string $siteURL) {
		if(!($siteData = $this->getSiteDataFromURL($siteURL))) {
			throw new Exception("Site URL is invalid: {$siteURL}");
		}

		return $this->getID($title, $siteData->id);
	}

	/**
	 * @param string $titleURL
	 * @param int    $siteID
	 *
	 * @return int
	 */
	private function addTitle(string $titleURL, int $siteID) : int {
		$query = $this->db->select('site, site_class')
		                  ->from('tracker_sites')
		                  ->where('id', $siteID)
		                  ->get();

		$titleData = $this->sites->{$query->row()->site_class}->getTitleData($titleURL, TRUE);

		//FIXME: getTitleData can fail, which will in turn cause the below to fail aswell, we should try and account for that
		if($titleData) {
			$this->db->insert('tracker_titles', array_merge($titleData, ['title_url' => $titleURL, 'site_id' => $siteID]));
			$titleID = $this->db->insert_id();

			$this->History->updateTitleHistory((int) $titleID, NULL, $titleData['latest_chapter'] ?? NULL, $titleData['last_updated'] ?? date("Y-m-d H:i:s", now()));
		} else {
			log_message('error', "getTitleData failed for: {$query->row()->site_class} | {$titleURL}");
		}
		return $titleID ?? 0;
	}


	/**
	 * @param int    $titleID
	 * @param string? $latestChapter
	 *
	 * @return bool
	 */
	public function updateByID(int $titleID, ?string $latestChapter) : bool {
		//FIXME: Really not too happy with how we're doing history stuff here, it just feels messy.
		$query = $this->db->select('latest_chapter AS current_chapter')
		                  ->from('tracker_titles')
		                  ->where('id', $titleID)
		                  ->get();
		$row = $query->row();

		//TODO (CHECK): If failed_checks changes won't that trigger affected_rows?
		$success = $this->db->set(['latest_chapter' => $latestChapter, 'failed_checks' => 0]) //last_updated gets updated via a trigger if something changes
		                    ->where('id', $titleID)
		                    ->update('tracker_titles');

		if($this->db->affected_rows() > 0) {
			//Clear hidden latest chapter
			$this->db->set(['ignore_chapter' => 'NULL', 'last_updated' => 'last_updated'], NULL, FALSE)
			         ->where('title_id', $titleID)
			         ->update('tracker_chapters');
		}

		//Update History
		//NOTE: To avoid doing another query to grab the last_updated time, we just use time() which <should> get the same thing.
		//FIXME: The <preferable> solution here is we'd just check against the last_updated time, but that can have a few issues.
		$this->History->updateTitleHistory($titleID, $row->current_chapter, $latestChapter, date('Y-m-d H:i:s'));

		return (bool) $success;
	}

	public function updateFailedChecksByID(int $titleID) : bool {
		$success = $this->db->set('failed_checks', 'failed_checks + 1', FALSE)
		                    ->where('id', $titleID)
		                    ->update('tracker_titles');

		return $success;
	}

	/**
	 * @param string $site_url
	 *
	 * @return stdClass|object|null
	 */
	public function getSiteDataFromURL(string $site_url) {
		$query = $this->db->select('*')
		                  ->from('tracker_sites')
		                  ->where('site', $site_url)
		                  ->get();

		return $query->row();
	}
}
