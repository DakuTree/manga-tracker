<?php declare(strict_types=1); defined('BASEPATH') OR exit('No direct script access allowed');

class Tracker_Admin_Model extends Tracker_Base_Model {
	public function __construct() {
		parent::__construct();
	}

	/**
	 * Checks for any series that haven't updated in 16 hours and updates them.
	 * This is ran every 4 hours via a cron job.
	 */
	public function updateLatestChapters() {
		// @formatter:off
		$query = $this->db
			->select('
				tracker_titles.id as title_id,
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
			->group_start()
				//Check if title is marked as on-going...
				->where('tracker_titles.status', 0)
				//AND matches one of where queries below
				->group_start()
					//Then check if it's NULL (only occurs for new series)
					->where('latest_chapter', NULL)
					//OR if it hasn't updated within the past 12 hours AND isn't a custom update site
					->or_group_start()
						->where('tracker_sites.use_custom', 'N')
						->where('last_checked < DATE_SUB(NOW(), INTERVAL 12 HOUR)')
					->group_end()
					//OR it is a custom update site, has more than one follower and hasn't updated within the past 72 hours.
					->or_group_start()
						->where('tracker_titles.id IN (
							SELECT title_id
							FROM tracker_chapters
							GROUP BY title_id
							HAVING COUNT(title_id) > 1
						)', NULL, FALSE)

						->where('last_checked < DATE_SUB(NOW(), INTERVAL 72 HOUR)')
					->group_end()
					//OR it is a custom update site and hasn't updated within the past 120 hours (5 days)
					->or_where('last_checked < DATE_SUB(NOW(), INTERVAL 120 HOUR)')
				->group_end()
			->group_end()
			->or_group_start()
				//Check if title is marked as complete...
				->where('tracker_titles.status', 1)
				//Then check if it hasn't updated within the past week
				->where('last_checked < DATE_SUB(NOW(), INTERVAL 1 WEEK)')
			->group_end()
			//Status 2 (One-shot) & 255 (Ignore) are both not updated intentionally.
			->group_by('tracker_titles.id, tracker_chapters.active')
			//Check if the series is actually being tracked by someone
			->having('timestamp IS NOT NULL')
			//AND if it's currently marked as active by the user
			->having('tracker_chapters.active', 'Y')
			//AND if they have been active in the last 120 hours (5 days)
			->having('timestamp > DATE_SUB(NOW(), INTERVAL 120 HOUR)')
			->order_by('tracker_titles.title', 'ASC')
			->get();
		// @formatter:on

		if($query->num_rows() > 0) {
			foreach ($query->result() as $row) {
				print "> {$row->title} <{$row->site_class}> | <{$row->title_id}>"; //Print this prior to doing anything so we can more easily find out if something went wrong
				$titleData = $this->sites->{$row->site_class}->getTitleData($row->title_url);
				if(is_array($titleData) && !is_null($titleData['latest_chapter'])) {
					//FIXME: "At the moment" we don't seem to be doing anything with TitleData['last_updated'].
					//       Should we even use this? Y/N
					if($this->Tracker->title->updateByID((int) $row->title_id, $titleData['latest_chapter'])) {
						//Make sure last_checked is always updated on successful run.
						//CHECK: Is there a reason we aren't just doing this in updateByID?
						$this->db->set('last_checked', 'CURRENT_TIMESTAMP', FALSE)
						         ->where('id', $row->title_id)
						         ->update('tracker_titles');

						print " - ({$titleData['latest_chapter']})\n";
					} else {
						log_message('error', "{$row->title} failed to update successfully");

						print " - Something went wrong?\n";
					}
				} else {
					log_message('error', "{$row->title} failed to update successfully");
					$this->Tracker->title->updateFailedChecksByID((int) $row->title_id);

					print " - FAILED TO PARSE\n";
				}
			}
		}
	}

	/**
	 * Intended to be only used as a quick way to update all series on a site after a bug.
	 *
	 * @param string      $site
	 * @param null|string $last_checked
	 */
	public function updateAllTitlesBySite(string $site, ?string $last_checked = NULL) {
		// @formatter:off
		$query = $this->db
			->select('
				tracker_titles.id as title_id,
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
			->where('tracker_sites.site_class', $site)
			->group_start()
				//Check if title is marked as on-going...
				->where('tracker_titles.status', 0)
				//Check if title is marked as complete...
				->or_where('tracker_titles.status', 1)
			->group_end()
			//Status 2 (One-shot) & 255 (Ignore) are both not updated intentionally.
			->group_by('tracker_titles.id, tracker_chapters.active')
			//Check if the series is actually being tracked by someone
			->having('timestamp IS NOT NULL')
			//AND if it's currently marked as active by the user
			->having('tracker_chapters.active', 'Y')
			//AND if they have been active in the last 120 hours (5 days)
			->having('timestamp > DATE_SUB(NOW(), INTERVAL 120 HOUR)')
			->order_by('tracker_titles.last_checked', 'ASC');
		// @formatter:on
		if(!is_null($last_checked)) {
			$query = $query->where('tracker_titles.last_checked >', $last_checked);
		}
		$query = $query->get();

		if($query->num_rows() > 0) {
			foreach ($query->result() as $row) {
				print "> {$row->title} <{$row->site_class}> | <{$row->title_id}>"; //Print this prior to doing anything so we can more easily find out if something went wrong
				$titleData = $this->sites->{$row->site_class}->getTitleData($row->title_url);
				if(is_array($titleData) && !is_null($titleData['latest_chapter'])) {
					//FIXME: "At the moment" we don't seem to be doing anything with TitleData['last_updated'].
					//       Should we even use this? Y/N
					if($this->Tracker->title->updateByID((int) $row->title_id, $titleData['latest_chapter'])) {
						//Make sure last_checked is always updated on successful run.
						//CHECK: Is there a reason we aren't just doing this in updateByID?
						$this->db->set('last_checked', 'CURRENT_TIMESTAMP', FALSE)
						         ->where('id', $row->title_id)
						         ->update('tracker_titles');

						print " - ({$titleData['latest_chapter']})\n";
					} else {
						log_message('error', "{$row->title} failed to update successfully");

						print " - Something went wrong?\n";
					}
				} else {
					log_message('error', "{$row->title} failed to update successfully");
					$this->Tracker->title->updateFailedChecksByID((int) $row->title_id);

					print " - FAILED TO PARSE\n";
				}
			}
		}
	}

	/**
	 * Checks for any sites which support custom updating (usually via following lists) and updates them.
	 * This is run hourly.
	 */
	public function updateCustom() {
		$query = $this->db->select('*')
		                  ->from('tracker_sites')
		                  ->where('status', 'enabled')
		                  ->where('tracker_sites.use_custom', 'Y')
		                  ->get();

		$sites = $query->result_array();
		foreach ($sites as $site) {
			$siteClass = $this->sites->{$site['site_class']};
			if($titleDataList = $siteClass->doCustomUpdate()) {
				foreach ($titleDataList as $titleURL => $titleData) {
					print "> {$titleData['title']} <{$site['site_class']}>"; //Print this prior to doing anything so we can more easily find out if something went wrong
					if(is_array($titleData) && !is_null($titleData['latest_chapter'])) {
						if($dbTitleData = $this->Tracker->title->getID($titleURL, (int) $site['id'], FALSE, TRUE)) {
							if($this->sites->{$site['site_class']}->doCustomCheck($dbTitleData['latest_chapter'], $titleData['latest_chapter'])) {
								$titleID = $dbTitleData['id'];
								if($this->Tracker->title->updateByID((int) $titleID, $titleData['latest_chapter'])) {
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
								print " - Failed Check (DB: '{$dbTitleData['latest_chapter']}' || UPDATE: '{$titleData['latest_chapter']}')\n";
							}
						} else {
							if($siteClass->customType === 1) {
								//We only need to log if following page is missing title, not latest releases
								log_message('error', "CUSTOM: {$titleData['title']} - {$site['site_class']} || Title does not exist in DB??");
								print " - Title doesn't currently exist in DB? Maybe different language or title stub change? ($titleURL)\n";
							} else {
								print " - Title isn't currently tracked.\n";
							}
						}
					} else {
						log_message('error', "CUSTOM: {$titleData['title']} - {$site['site_class']} failed to custom update successfully");
						print " - FAILED TO PARSE\n";
					}
				}
			}
		}
	}

	public function refollowCustom() {
		$query = $this->db->select('tracker_titles.id, tracker_titles.title_url, tracker_sites.site_class')
		                  ->from('tracker_titles')
		                  ->join('tracker_sites', 'tracker_sites.id = tracker_titles.site_id', 'left')
		                  ->where('tracker_titles.followed','N')
		                  ->where('tracker_titles !=', '255')
		                  ->where('tracker_sites.status', 'enabled')
		                  ->where('tracker_sites.use_custom', 'Y')
		                  ->get();

		if($query->num_rows() > 0) {
			foreach($query->result() as $row) {
				$titleData = $this->Tracker->sites->{$row->site_class}->getTitleData($row->title_url, TRUE);

				if($titleData) {
					$titleData = array_intersect_key($titleData, array_flip(['followed']));

					if(!empty($titleData)) {
						$this->db->set($titleData)
						         ->where('id', $row->id)
						         ->update('tracker_titles');

						print "> {$row->site_class}:{$row->id}:{$row->title_url} FOLLOWED\n";
					} else {
						print "> {$row->site_class}:{$row->id}:{$row->title_url} FAILED (NO FOLLOWED)\n";
					}
				} else {
					log_message('error', "getTitleData failed for: {$row->site_class} | {$row->title_url}");
					print "> {$row->site_class}:{$row->id}:{$row->title_url} FAILED (NO TITLEDATA)\n";
				}
			}
		}
	}

	/**
	 * Checks every series to see if title has changed, and update if so.
	 * This is ran once a month via a cron job
	 */
	public function updateTitles() {
		// @formatter:off
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
				tracker_titles.last_updated
			')
			->from('tracker_titles')
			->join('tracker_sites', 'tracker_sites.id = tracker_titles.site_id', 'left')
			->where('tracker_sites.status', 'enabled')

			->group_by('tracker_titles.id')
			->order_by('tracker_titles.title', 'ASC')
			->get();
		// @formatter:on

		if($query->num_rows() > 0) {
			foreach ($query->result() as $row) {
				print "> {$row->title} <{$row->site_class}>"; //Print this prior to doing anything so we can more easily find out if something went wrong
				$titleData = $this->sites->{$row->site_class}->getTitleData($row->title_url);
				if($titleData['title'] && is_array($titleData) && !is_null($titleData['latest_chapter'])) {
					if($titleData['title'] !== $row->title) {
						$this->db->set('title', $titleData['title'])
						         ->where('id', $row->id)
						         ->update('tracker_titles');
						//TODO: Add to history somehow?
						print " - NEW TITLE ({$titleData['title']})\n";
					} else {
						print " - TITLE NOT CHANGED\n";
					}

					//We might as well try to update as well.
					if($this->Tracker->title->updateByID((int) $row->id, $titleData['latest_chapter'])) {
						$this->db->set('last_checked', 'CURRENT_TIMESTAMP', FALSE)
						         ->where('id', $row->id)
						         ->update('tracker_titles');
					}
				} else {
					log_message('error', "{$row->title} failed to update title successfully");
					print " - FAILED TO PARSE\n";
				}
			}
		}
	}

	public function incrementRequests() : void {
		$temp_now = new DateTime();
		$temp_now->setTimezone(new DateTimeZone('America/New_York'));
		$date = $temp_now->format('Y-m-d');

		$query = $this->db->select('1')
		                  ->from('site_stats')
		                  ->where('date', $date)
		                  ->get();

		if($query->num_rows() > 0) {
			$this->db->set('total_requests', 'total_requests+1', FALSE)
			         ->where('date', $date)
			         ->update('site_stats');
		} else {
			$this->db->insert('site_stats', [
				'date'           => $date,
				'total_requests' => 1
			]);
		}
	}

	public function getNextUpdateTime(string $format = "%H:%I:%S") : string {
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
		return $interval->format($format);
	}
}
