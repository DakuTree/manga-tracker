<?php declare(strict_types=1); defined('BASEPATH') OR exit('No direct script access allowed');

class Tracker_Admin_Model extends Tracker_Base_Model {
	public function __construct() {
		parent::__construct();
	}


	/**
	 * Checks for any titles that haven't updated in 16 hours and updates them.
	 * This is ran every 6 hours via a cron job.
	 */
	public function updateLatestChapters() {
		$query = $this->db
			->select('
				tracker_titles.id,
				tracker_titles.title,
				tracker_titles.title_url,
				tracker_titles.status,
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
			->where('tracker_sites.status', 'enabled')
			->where('tracker_chapters.active', 'Y') //CHECK: Does this apply BEFORE the GROUP BY/HAVING is done?
			//Check if title is marked as on-going...
			->where('(tracker_titles.status = 0', NULL, FALSE) //TODO: Each title should have specific interval time?
			//Then check if it's NULL (only occurs for new series)
			->where('(`latest_chapter` = NULL', NULL, FALSE)
			//Or if it hasn't updated within the past 12 hours AND isn't MangaFox
			//FIXME: We <really> shouldn't have to specify specific sites here. A DB column would probably be better.
			->or_where('(NOT tracker_sites.site_class = "MangaFox" AND `last_checked` < DATE_SUB(NOW(), INTERVAL 12 HOUR))', NULL, FALSE)
			//Or if it hasn't updated within the past 12 hours AND isn't MangaFox
			//FIXME: See above.
			->or_where('`last_checked` < DATE_SUB(NOW(), INTERVAL 36 HOUR)))', NULL, FALSE)
			//Check if title is marked as complete...
			->or_where('(tracker_titles.status = 1', NULL, FALSE)
			//Then check if it hasn't updated within the past week
			->where('`last_checked` < DATE_SUB(NOW(), INTERVAL 1 WEEK))', NULL, FALSE)
			//Status 2 (One-shot) & 255 (Ignore) are both not updated intentionally.
			->group_by('tracker_titles.id')
			->having('timestamp IS NOT NULL')
			->having('timestamp > DATE_SUB(NOW(), INTERVAL 120 HOUR)')
			->order_by('tracker_titles.title', 'ASC')
			->get();

		if($query->num_rows() > 0) {
			foreach ($query->result() as $row) {
				print "> {$row->title} <{$row->site_class}>"; //Print this prior to doing anything so we can more easily find out if something went wrong
				$titleData = $this->sites->{$row->site_class}->getTitleData($row->title_url);
				if(is_array($titleData) && !is_null($titleData['latest_chapter'])) {
					//FIXME: "At the moment" we don't seem to be doing anything with TitleData['last_updated'].
					//       Should we even use this? Y/N
					if($this->Tracker->title->updateByID((int) $row->id, $titleData['latest_chapter'])) {
						//Make sure last_checked is always updated on successful run.
						//CHECK: Is there a reason we aren't just doing this in updateByID?
						$this->db->set('last_checked', 'CURRENT_TIMESTAMP', FALSE)
						         ->where('id', $row->id)
						         ->update('tracker_titles');

						print " - ({$titleData['latest_chapter']})\n";
					}
				} else {
					log_message('error', "{$row->title} failed to update successfully");
					print " - FAILED TO PARSE\n";
				}
			}
		}
	}

	public function updateCustom() {
		$query = $this->db->select('*')
		                  ->from('tracker_sites')
		                  ->where('status', 'enabled')
		                  ->get();

		$sites = $query->result_array();
		foreach ($sites as $site) {
			if($titleDataList = $this->sites->{$site['site_class']}->doCustomUpdate()) {
				foreach ($titleDataList as $titleURL => $titleData) {
					print "> {$titleData['title']} <{$site['site_class']}>"; //Print this prior to doing anything so we can more easily find out if something went wrong
					if(is_array($titleData) && !is_null($titleData['latest_chapter'])) {
						if($dbTitleData = $this->title->getID($titleURL, (int) $site['id'], FALSE, TRUE)) {
							if($this->sites->{$site['site_class']}->doCustomCheck($dbTitleData['latest_chapter'], $titleData['latest_chapter'])) {
								$titleID = $dbTitleData['id'];
								if($this->title->updateByID((int) $titleID, $titleData['latest_chapter'])) {
									//Make sure last_checked is always updated on successful run.
									//CHECK: Is there a reason we aren't just doing this in updateByID?
									$this->db->set('last_checked', 'CURRENT_TIMESTAMP', FALSE)
									         ->where('id', $titleID)
									         ->update('tracker_titles');

									print " - ({$titleData['latest_chapter']})\n";
								} else {
									print " - Title doesn't exist? ($titleID)\n";
								}
							} else {
								print " - Failed Check.\n";
							}
						} else {
							log_message('error', "{$titleData['title']} || Title does not exist in DB??");
							print " - Possibly diff language than in DB? ($titleURL)\n";
						}
					} else {
						log_message('error', "{$titleData['title']} failed to custom update successfully");
						print " - FAILED TO PARSE\n";
					}
				}
			}
		}
	}

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
}
