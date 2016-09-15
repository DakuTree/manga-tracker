<?php declare(strict_types=1); defined('BASEPATH') OR exit('No direct script access allowed');

class Tracker_Model extends CI_Model {
	public $sites;
	public $enabledCategories;

	public function __construct() {
		parent::__construct();

		$this->load->database();

		$this->enabledCategories = [
			'reading'      => 'Reading',
			'on-hold'      => 'On-Hold',
			'plan-to-read' => 'Plan to Read'
		];
		if($this->User_Options->get('category_custom_1') == 'enabled') {
			$this->enabledCategories['custom1'] = $this->User_Options->get('category_custom_1_text');
		}
		if($this->User_Options->get('category_custom_2') == 'enabled') {
			$this->enabledCategories['custom2'] = $this->User_Options->get('category_custom_2_text');
		}
		if($this->User_Options->get('category_custom_3') == 'enabled') {
			$this->enabledCategories['custom3'] = $this->User_Options->get('category_custom_3_text');
		}

		require_once(APPPATH.'models/Site_Model.php');
		$this->sites = new Sites_Model;
	}

	/****** GET TRACKER *******/
	public function get_tracker_from_user_id(int $userID) {
		$query = $this->db
			->select('tracker_chapters.*,
			          tracker_titles.site_id, tracker_titles.title, tracker_titles.title_url, tracker_titles.latest_chapter, tracker_titles.last_updated AS title_last_updated,
			          tracker_sites.site, tracker_sites.site_class, tracker_sites.status AS site_status')
			->from('tracker_chapters')
			->join('tracker_titles', 'tracker_chapters.title_id = tracker_titles.`id', 'left')
			->join('tracker_sites', 'tracker_sites.id = tracker_titles.site_id', 'left')
			->where('tracker_chapters.user_id', $userID)
			->get();

		$arr = [];
		foreach($this->enabledCategories as $category => $name) {
			$arr[$category] = [
				'name'         => $name,
				'manga'        => [],
				'unread_count' => 0
			];
		}
		if($query->num_rows() > 0) {
			foreach ($query->result() as $row) {
				$is_unread     = intval($row->latest_chapter == $row->current_chapter ? '1' : '0');
				$arr[$row->category]['unread_count'] = (($arr[$row->category]['unread_count'] ?? 0) + !$is_unread);
				$arr[$row->category]['manga'][] = [
					'id' => $row->id,
					'generated_current_data' => $this->sites->{$row->site_class}->getChapterData($row->title_url, $row->current_chapter),
					'generated_latest_data'  => $this->sites->{$row->site_class}->getChapterData($row->title_url, $row->latest_chapter),
					'full_title_url'        =>  $this->sites->{$row->site_class}->getFullTitleURL($row->title_url),

					'new_chapter_exists'    => $is_unread,
					'tag_list'              => $row->tags,
					'has_tags'              => !empty($row->tags),

					'title_data' => [
						'id'              => $row->title_id,
						'title'           => $row->title,
						'title_url'       => $row->title_url,
						'latest_chapter'  => $row->latest_chapter,
						'current_chapter' => $row->current_chapter,
						'last_updated'    => $row->title_last_updated
					],
					'site_data' => [
						'id'         => $row->site_id,
						'site'       => $row->site,
						'status'     => $row->site_status
					]
				];
			}

			//NOTE: This does not sort in the same way as tablesorter, but it works better.
			foreach (array_keys($arr) as $category) {
				usort($arr[$category]['manga'], function ($a, $b) {
					return strtolower("{$a['new_chapter_exists']} - {$a['title_data']['title']}") <=> strtolower("{$b['new_chapter_exists']} - {$b['title_data']['title']}");
				});
			}
		}
		return $arr;
	}

	public function getSiteDataFromURL(string $site_url) {
		$query = $this->db->select('id, site_class')
		                  ->from('tracker_sites')
		                  ->where('site', $site_url)
		                  ->get();

		if($query->num_rows() > 0) {
			$siteData = $query->row();
		}

		return $siteData ?? FALSE;
	}

	public function getTitleID(string $titleURL, int $siteID) {
		$query = $this->db->select('id')
		                  ->from('tracker_titles')
		                  ->where('title_url', $titleURL)
		                  ->where('site_id', $siteID)
		                  ->get();

		if($query->num_rows() > 0) {
			$titleID = $query->row('id');
		} else {
			//TODO: Check if title is valid URL!
			$titleID = $this->addTitle($titleURL, $siteID);
		}

		return $titleID;
	}

	public function updateTracker(int $userID, string $site, string $title, string $chapter) : bool {
		$success = FALSE;
		if($siteData = $this->Tracker->getSiteDataFromURL($site)) {
			//Validate user input
			if(!$this->sites->{$siteData->site_class}->isValidTitleURL($title)) {
				//Error is already logged via isValidTitleURL
				return FALSE;
			}
			if(!$this->sites->{$siteData->site_class}->isValidChapter($chapter)) {
				//Error is already logged via isValidChapter
				return FALSE;
			}

			//NOTE: If the title doesn't exist it will be created. This maybe isn't perfect, but it works for now.
			$titleID = $this->Tracker->getTitleID($title, (int) $siteData->id);
			if($titleID === 0) {
				//Something went wrong.
				log_message('error', "TitleID = 0 for {$title} @ {$siteData->id}");
				return FALSE;
			}
			if($this->db->select('*')->where('user_id', $userID)->where('title_id', $titleID)->get('tracker_chapters')->num_rows() > 0) {
				$success = $this->db->set(['current_chapter' => $chapter, 'last_updated' => NULL])
				                    ->where('user_id', $userID)
				                    ->where('title_id', $titleID)
				                    ->update('tracker_chapters');
			} else {
				$success = $this->db->insert('tracker_chapters', [
					'user_id'         => $userID,
					'title_id'        => $titleID,
					'current_chapter' => $chapter,
					'category'        => $this->User_Options->get_by_userid('default_series_category', $userID)
				]);
			}
		}
		return (bool) $success;
	}

	public function updateTrackerByID(int $userID, int $chapterID, string $chapter) : bool {
		$success = $this->db->set(['current_chapter' => $chapter, 'last_updated' => NULL])
		                    ->where('user_id', $userID)
		                    ->where('id', $chapterID)
		                    ->update('tracker_chapters');

		return (bool) $success;
	}

	public function deleteTrackerByID(int $userID, int $chapterID) {
		$success = $this->db->where('user_id', $userID)
		                    ->where('id', $chapterID)
		                    ->delete('tracker_chapters');

		return (bool) $success;
	}
	private function updateTitleById(int $id, string $latestChapter) {
		$success = $this->db->set(['latest_chapter' => $latestChapter]) //last_updated gets updated via a trigger if something changes
		                    ->where('id', $id)
		                    ->update('tracker_titles');

		return (bool) $success;
	}
	private function updateTitleDataById(int $id, array $titleData) {
		$success = $this->db->set($titleData)
		                    ->where('id', $id)
		                    ->update('tracker_titles');

		return (bool) $success;
	}
	private function addTitle(string $titleURL, int $siteID) {
		$query = $this->db->select('site, site_class')
		                  ->from('tracker_sites')
		                  ->where('id', $siteID)
		                  ->get();

		$titleData = $this->sites->{$query->row()->site_class}->getTitleData($titleURL);
		$this->db->insert('tracker_titles', array_merge($titleData, ['title_url' => $titleURL, 'site_id' => $siteID]));
		return $this->db->insert_id();
	}

	/**
	 * Checks for any titles that haven't updated in 16 hours and updates them.
	 * This is ran every 6 hours via a cron job.
	 */
	public function updateLatestChapters() {
		$query = $this->db->select('
		                      tracker_titles.id,
		                      tracker_titles.title,
		                      tracker_titles.title_url,
		                      tracker_sites.site,
		                      tracker_sites.site_class,
		                      tracker_sites.status,
		                      tracker_titles.latest_chapter,
		                      tracker_titles.last_updated,
		                      from_unixtime(MAX(auth_users.last_login)) AS timestamp
		                  ')
		                  ->from('tracker_titles')
		                  ->join('tracker_sites', 'tracker_sites.id = tracker_titles.site_id', 'left')
		                  ->join('tracker_chapters', 'tracker_titles.id = tracker_chapters.title_id', 'left')
		                  ->join('auth_users', 'tracker_chapters.user_id = auth_users.id', 'left')
		                  ->where('tracker_sites.status = "enabled"')
		                  ->where('(`complete` = "N" AND (`latest_chapter` = NULL OR `last_checked` < DATE_SUB(NOW(), INTERVAL 12 HOUR)))', NULL, FALSE) //TODO: Each title should have specific interval time?
		                  ->or_where('(`complete` = "Y" AND `last_checked` < DATE_SUB(NOW(), INTERVAL 1 WEEK))', NULL, FALSE)
		                  ->group_by('tracker_titles.id')
		                  ->having('timestamp IS NOT NULL')
		                  ->having('timestamp > DATE_SUB(NOW(), INTERVAL 120 HOUR)')
		                  ->order_by('tracker_titles.title', 'ASC')
		                  ->get();

		if($query->num_rows() > 0) {
			foreach ($query->result() as $row) {
				print "> {$row->title} <{$row->site_class}>"; //Print this prior to doing anything so we can more easily find out if something went wrong
				$titleData = $this->sites->{$row->site_class}->getTitleData($row->title_url);
				if(!is_null($titleData['latest_chapter'])) {
					if($this->updateTitleById((int) $row->id, $titleData['latest_chapter'])) {
						//Make sure last_checked is always updated on successful run.
						$this->db->set('last_checked', 'CURRENT_TIMESTAMP', FALSE)
						         ->where('id', $row->id)
						         ->update('tracker_titles');

						print " - ({$titleData['latest_chapter']})\n";
					}
				} else {
					log_message('error', "{$row->title} failed to update successfully");
					print " - FAILED TO PARSE\n";
					//FIXME: Something went wrong! Alert admin!
				}
			}
		}
	}

	public function exportTrackerFromUserID(int $userID) {
		$query = $this->db
			->select('tracker_chapters.current_chapter,
			          tracker_chapters.category,
			          tracker_titles.title_url,
			          tracker_sites.site')
			->from('tracker_chapters')
			->join('tracker_titles', 'tracker_chapters.title_id = tracker_titles.`id', 'left')
			->join('tracker_sites', 'tracker_sites.id = tracker_titles.site_id', 'left')
			->where('tracker_chapters.user_id', $userID)
			->get();

		$arr = [];
		if($query->num_rows() > 0) {
			foreach ($query->result() as $row) {
				$arr[$row->category][] = [
					'site'            => $row->site,
					'title_url'       => $row->title_url,
					'current_chapter' => $row->current_chapter
				];
			}

			return $arr;
		}
	}

	public function importTrackerFromJSON(string $json_string) : array {
		//We already know the this is a valid JSON string as it was validated by form_validator.
		$json = json_decode($json_string, TRUE);

		/*
		 * 0 = Success
		 * 1 = Invalid keys.
		 * 2 = Has failed rows
		 */
		$status = ['code' => 0, 'failed_rows' => []];

		$categories = array_keys($json);
		if(count($categories) === array_intersect(['reading', 'on-hold', 'plan-to-read', 'custom1', 'custom2', 'custom3'], $categories)) {
			$json_keys = array_keys(call_user_func_array('array_merge', $json));

			if(count($json_keys) === 3 && !array_diff(array('site', 'title_url', 'current_chapter'), $json_keys)) {
				foreach($categories as $category) {
					foreach($json[$category] as $row) {
						$success = $this->updateTracker($this->User->id, $row['site'], $row['title_url'], $row['current_chapter']);
						if(!$success) {
							$status['code']          = 2;
							$status['failed_rows'][] = $row;
						}
					}
				}
			} else {
				$status['code'] = 1;
			}
		} else {
			$status['code'] = 1;
		}
		return $status;
	}

	public function deleteTrackerByIDList(array $idList) : array {
		/*
		 * 0 = Success
		 * 1 = Invalid IDs
		 */
		$status = ['code' => 0];

		foreach($idList as $id) {
			if(!(ctype_digit($id) && $this->deleteTrackerByID($this->User->id, (int) $id))) {
				$status['code'] = 1;
			}
		}

		return $status;
	}

	public function setCategoryByIDList(array $idList, string $category) : array {
		/*
		 * 0 = Success
		 * 1 = Invalid IDs
		 * 2 = Invalid category
		 */
		$status = ['code' => 0];

		if(in_array($category, array_keys($this->enabledCategories))) {
			foreach($idList as $id) {
				if(!(ctype_digit($id) && $this->setCategoryTrackerByID($this->User->id, (int) $id, $category))) {
					$status['code'] = 1;
				}
			}
		} else {
			$status['code'] = 2;
		}

		return $status;
	}
	public function setCategoryTrackerByID(int $userID, int $chapterID, string $category) : bool {
		$success = $this->db->set(['category' => $category, 'last_updated' => NULL])
		                    ->where('user_id', $userID)
		                    ->where('id', $chapterID)
		                    ->update('tracker_chapters');

		return (bool) $success;
	}


	public function updateTagsByID(int $userID, int $chapterID, string $tag_string) : bool {
		$success = FALSE;
		if(preg_match("/^[a-z0-9-_,]{0,255}$/", $tag_string)) {
			$success = $this->db->set(['tags' => $tag_string, 'last_updated' => NULL])
			                    ->where('user_id', $userID)
			                    ->where('id', $chapterID)
			                    ->update('tracker_chapters');
		}

		return (bool) $success;
	}

	public function getUsedCategories(int $userID) : array {
		$usedCategories = [];

		$query = $this->db->distinct()
		                  ->select('category')
		                  ->from('tracker_chapters')
		                  ->get();

		return array_column($query->result_array(), 'category');
	}

	//FIXME: Should this be moved elsewhere??
	public function getNextUpdateTime() : string {
		$temp_now = new DateTime();
		$temp_now->setTimezone(new DateTimeZone('America/New_York'));
		$temp_now_formatted = $temp_now->format('Y-m-d H:i:s');

		//NOTE: PHP Bug: DateTime:diff doesn't play nice with setTimezone, so we need to create another DT object
		$now         = new DateTime($temp_now_formatted);
		$future_date = new DateTime($temp_now_formatted);
		$now_hour    = (int) $now->format('H');
		if($now_hour < 4) {
			//Time until 4am
			$future_date->setTime(4, 00);
		} elseif($now_hour < 8) {
			//Time until 8am
			$future_date->setTime(8, 00);
		} elseif($now_hour < 12) {
			//Time until 12pm
			$future_date->setTime(12, 00);
		} elseif($now_hour < 16) {
			//Time until 4pm
			$future_date->setTime(16, 00);
		} elseif($now_hour < 20) {
			//Time until 8pm
			$future_date->setTime(20, 00);
		} else {
			//Time until 12am
			$future_date->setTime(00, 00);
			$future_date->add(new DateInterval('P1D'));
		}

		$interval = $future_date->diff($now);
		return $interval->format("%H:%I:%S");
	}

	public function reportBug(string $text, $userID = NULL, $url = NULL) : bool {
		$this->load->library('email');

		//This is pretty barebones bug reporting, and honestly not a great way to do it, but it works for now (until the Github is public).
		$body = "".
		(!is_null($url) && !empty($url) ? "URL: ".htmlspecialchars(substr($url, 0, 255))."<br>\n" : "").
		"Submitted by: ".$this->input->ip_address().(!is_null($userID) ? "| {$userID}" : "")."<br>\n".
		"<br>Bug report: ".htmlspecialchars(substr($text, 0, 1000));

		$success = TRUE;
		$this->email->from('bug-report@trackr.moe', $this->config->item('site_title', 'ion_auth'));
		$this->email->to($this->config->item('admin_email', 'ion_auth'));
		$this->email->subject($this->config->item('site_title', 'ion_auth')." - Bug Report");
		$this->email->message($body);
		if(!$this->email->send()) {
			$success = FALSE;
		}
		return $success;
	}

	/*************************************************/
	public function sites() {
		return $this;
	}
}
