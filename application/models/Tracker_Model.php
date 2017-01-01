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
			          tracker_titles.site_id, tracker_titles.title, tracker_titles.title_url, tracker_titles.latest_chapter, tracker_titles.last_updated AS title_last_updated, tracker_titles.status AS title_status, tracker_titles.last_checked > DATE_SUB(NOW(), INTERVAL 1 WEEK) AS title_active,
			          tracker_sites.site, tracker_sites.site_class, tracker_sites.status AS site_status')
			->from('tracker_chapters')
			->join('tracker_titles', 'tracker_chapters.title_id = tracker_titles.id', 'left')
			->join('tracker_sites', 'tracker_sites.id = tracker_titles.site_id', 'left')
			->where('tracker_chapters.user_id', $userID)
			->where('tracker_chapters.active', 'Y')
			->get();

		$arr = ['series' => [], 'has_inactive' => FALSE];
		foreach($this->enabledCategories as $category => $name) {
			$arr['series'][$category] = [
				'name'         => $name,
				'manga'        => [],
				'unread_count' => 0
			];
		}
		if($query->num_rows() > 0) {
			foreach ($query->result() as $row) {
				$is_unread     = intval($row->latest_chapter == $row->current_chapter ? '1' : '0');
				$arr['series'][$row->category]['unread_count'] = (($arr['series'][$row->category]['unread_count'] ?? 0) + !$is_unread);
				$data = [
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
						'last_updated'    => $row->title_last_updated,
						//NOTE: active is used to warn the user if a title hasn't updated (Maybe due to nobody active tracking it or other reasons).
						//      This will ONLY be false when an actively updating series (site enabled & title status = 0) hasn't updated within the past week.
						'active'          => ($row->site_status == 'disabled' || in_array($row->title_status, [/*complete*/ 1, /* one-shot */ 2, /* ignored */ 255]) || $row->title_active == 1)
					],
					'site_data' => [
						'id'         => $row->site_id,
						'site'       => $row->site,
						'status'     => $row->site_status
					]
				];
				$arr['series'][$row->category]['manga'][] = $data;

				if(!$arr['has_inactive']) $arr['has_inactive'] = !$data['title_data']['active'];
			}

			//CHECK: Is this good for speed?
			//NOTE: This does not sort in the same way as tablesorter, but it works better.
			switch($this->User_Options->get('list_sort_type')) {
				case 'unread':
					foreach (array_keys($arr['series']) as $category) {
						usort($arr['series'][$category]['manga'], function ($a, $b) {
							$a_text = strtolower("{$a['new_chapter_exists']} - {$a['title_data']['title']}");
							$b_text = strtolower("{$b['new_chapter_exists']} - {$b['title_data']['title']}");

							if($this->User_Options->get('list_sort_order') == 'asc') {
								return $a_text <=> $b_text;
							} else {
								return $b_text <=> $a_text;
							}
						});
					}
					break;

				case 'alphabetical':
					foreach (array_keys($arr['series']) as $category) {
						usort($arr['series'][$category]['manga'], function ($a, $b) {
							$a_text = strtolower("{$a['title_data']['title']}");
							$b_text = strtolower("{$b['title_data']['title']}");

							if($this->User_Options->get('list_sort_order') == 'asc') {
								return $a_text <=> $b_text;
							} else {
								return $b_text <=> $a_text;
							}
						});
					}
					break;

				case 'my_status':
					foreach (array_keys($arr['series']) as $category) {
						usort($arr['series'][$category]['manga'], function ($a, $b) {
							$a_text = strtolower("{$a['generated_current_data']['number']}");
							$b_text = strtolower("{$b['generated_current_data']['number']}");

							if($this->User_Options->get('list_sort_order') == 'asc') {
								return $a_text <=> $b_text;
							} else {
								return $b_text <=> $a_text;
							}
						});
					}
					break;

				case 'latest':
					foreach (array_keys($arr['series']) as $category) {
						usort($arr['series'][$category]['manga'], function ($a, $b) {
							$a_text = strtolower("{$a['generated_latest_data']['number']}");
							$b_text = strtolower("{$b['generated_latest_data']['number']}");

							if($this->User_Options->get('list_sort_order') == 'asc') {
								return $a_text <=> $b_text;
							} else {
								return $b_text <=> $a_text;
							}
						});
					}
					break;

				default:
					break;
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

	public function getTitleID(string $titleURL, int $siteID, bool $create = TRUE, bool $returnData = FALSE) {
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
				$titleData = $this->sites->{$query->row('site_class')}->getTitleData($query->row('title_url'));
				if(!is_null($titleData['latest_chapter'])) {
					if($this->updateTitleById((int) $id, $titleData['latest_chapter'])) {
						//Make sure last_checked is always updated on successful run.
						//CHECK: Is there a reason we aren't just doing this in updateTitleById?
						$this->db->set('last_checked', 'CURRENT_TIMESTAMP', FALSE)
						         ->where('id', $id)
						         ->update('tracker_titles');
					}
				} else {
					log_message('error', "{$query->row('title')} failed to update successfully");
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

			$idQuery = $this->db->select('id')
			                    ->where('user_id', $userID)
			                    ->where('title_id', $titleID)
			                    ->get('tracker_chapters');
			if($idQuery->num_rows() > 0) {
				$success = (bool) $this->db->set(['current_chapter' => $chapter, 'active' => 'Y', 'last_updated' => NULL])
				                    ->where('user_id', $userID)
				                    ->where('title_id', $titleID)
				                    ->update('tracker_chapters');

				if($success) {
					$idQueryRow = $idQuery->row();
					$this->History->userUpdateTitle((int) $idQueryRow->id, $chapter);
				}
			} else {
				$category = $this->User_Options->get_by_userid('default_series_category', $userID);
				$success = (bool) $this->db->insert('tracker_chapters', [
					'user_id'         => $userID,
					'title_id'        => $titleID,
					'current_chapter' => $chapter,
					'category'        => $category
				]);

				if($success) {
					$this->History->userAddTitle((int) $this->db->insert_id(), $chapter, $category);
				}
			}
		}
		return $success;
	}

	public function updateTrackerByID(int $userID, int $chapterID, string $chapter) : bool {
		$success = (bool) $this->db->set(['current_chapter' => $chapter, 'active' => 'Y', 'last_updated' => NULL])
		                    ->where('user_id', $userID)
		                    ->where('id', $chapterID)
		                    ->update('tracker_chapters');

		if($success) {
			$this->History->userUpdateTitle($chapterID, $chapter);
		}
		return  $success;
	}

	public function deleteTrackerByID(int $userID, int $chapterID) {
		//Series are not fully deleted, they are just marked as inactive as to hide them from the user.
		//This is to allow user history to function properly.

		$success = $this->db->set(['active' => 'N', 'last_updated' => NULL])
		                    ->where('user_id', $userID)
		                    ->where('id', $chapterID)
		                    ->update('tracker_chapters');

		return (bool) $success;
	}
	private function updateTitleById(int $id, string $latestChapter) {
		//FIXME: Really not too happy with how we're doing history stuff here, it just feels messy.
		$query = $this->db->select('latest_chapter AS current_chapter')
		                  ->from('tracker_titles')
		                  ->where('id', $id)
		                  ->get();
		$row = $query->row();

		$success = $this->db->set(['latest_chapter' => $latestChapter]) //last_updated gets updated via a trigger if something changes
		                    ->where('id', $id)
		                    ->update('tracker_titles');

		//Update History
		//NOTE: To avoid doing another query to grab the last_updated time, we just use time() which <should> get the same thing.
		//FIXME: The <preferable> solution here is we'd just check against the last_updated time, but that can have a few issues.
		$this->History->updateTitleHistory($id, $row->current_chapter, $latestChapter, date('Y-m-d H:i:s'));

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

		$titleData = $this->sites->{$query->row()->site_class}->getTitleData($titleURL, TRUE);

		//FIXME: getTitleData can fail, which will in turn cause the below to fail aswell, we should try and account for that
		if($titleData) {
			$this->db->insert('tracker_titles', array_merge($titleData, ['title_url' => $titleURL, 'site_id' => $siteID]));
			$titleID = $this->db->insert_id();

			$this->History->updateTitleHistory((int) $titleID, NULL, $titleData['latest_chapter'], $titleData['last_updated']);
		} else {
			log_message('error', "getTitleData failed for: {$query->row()->site_class} | {$titleURL}");
		}
		return $titleID ?? 0;
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
			//Check if title is marked as on-going, and update if latest_chapter isn't set or hasn't updated within last 12 hours
			->where('(tracker_titles.status = 0 AND (`latest_chapter` = NULL OR `last_checked` < DATE_SUB(NOW(), INTERVAL 12 HOUR)))', NULL, FALSE) //TODO: Each title should have specific interval time?
			//Check if title is marked as complete, and update if it hasn't updated in the last week.
			->or_where('(tracker_titles.status = 1 AND `last_checked` < DATE_SUB(NOW(), INTERVAL 1 WEEK))', NULL, FALSE)
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
					if($this->updateTitleById((int) $row->id, $titleData['latest_chapter'])) {
						//Make sure last_checked is always updated on successful run.
						//CHECK: Is there a reason we aren't just doing this in updateTitleById?
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
		$sites = $this->getSites();
		foreach ($sites as $site) {
			if($titleDataList = $this->sites->{$site['site_class']}->doCustomUpdate()) {
				foreach ($titleDataList as $titleURL => $titleData) {
					print "> {$titleData['title']} <{$site['site_class']}>"; //Print this prior to doing anything so we can more easily find out if something went wrong
					if(is_array($titleData) && !is_null($titleData['latest_chapter'])) {
						if($dbTitleData = $this->getTitleID($titleURL, (int) $site['id'], FALSE, TRUE)) {
							if($this->sites->{$site['site_class']}->doCustomCheck($dbTitleData['latest_chapter'], $titleData['latest_chapter'])) {
								$titleID = $dbTitleData['id'];
								if($this->updateTitleById((int) $titleID, $titleData['latest_chapter'])) {
									//Make sure last_checked is always updated on successful run.
									//CHECK: Is there a reason we aren't just doing this in updateTitleById?
									$this->db->set('last_checked', 'CURRENT_TIMESTAMP', FALSE)
									         ->where('id', $titleID)
									         ->update('tracker_titles');

									print " - ({$titleData['latest_chapter']})\n";
								}
							} else {
								print " - Failed Check.\n";
							}
						} else {
							log_message('error', "{$titleData['title']} || Title does not exist in DB??");
						}
					} else {
						log_message('error', "{$titleData['title']} failed to custom update successfully");
						print " - FAILED TO PARSE\n";
					}
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
			->where('tracker_chapters.active', 'Y')
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
		if(count($categories) === count(array_intersect(['reading', 'on-hold', 'plan-to-read', 'custom1', 'custom2', 'custom3'], $categories))) {
			$json_keys = array_keys(call_user_func_array('array_merge', call_user_func_array('array_merge', $json)));

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
			} else {
				//Delete was successful, update history too.
				$this->History->userRemoveTitle((int) $id);
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
				} else {
					//Category update was successful, update history too.
					$this->History->userUpdateCategory((int) $id, $category);
				}
			}
		} else {
			$status['code'] = 2;
		}

		return $status;
	}
	public function setCategoryTrackerByID(int $userID, int $chapterID, string $category) : bool {
		$success = $this->db->set(['category' => $category, 'active' => 'Y', 'last_updated' => NULL])
		                    ->where('user_id', $userID)
		                    ->where('id', $chapterID)
		                    ->update('tracker_chapters');

		return (bool) $success;
	}

	public function updateTagsByID(int $userID, int $chapterID, string $tag_string) : bool {
		$success = FALSE;
		if(preg_match("/^[a-z0-9\\-_,:]{0,255}$/", $tag_string)) {
			$success = (bool) $this->db->set(['tags' => $tag_string, 'active' => 'Y', 'last_updated' => NULL])
			                           ->where('user_id', $userID)
			                           ->where('id', $chapterID)
			                           ->update('tracker_chapters');
		}

		if($success) {
			//Tag update was successful, update history
			$this->History->userUpdateTags($chapterID, $tag_string);
		}
		return $success;
	}

	public function favouriteChapter(int $userID, string $site, string $title, string $chapter) : array {
		$success = array(
			'status' => 'Something went wrong',
			'bool'   => FALSE
		);
		if($siteData = $this->Tracker->getSiteDataFromURL($site)) {
			//Validate user input
			if(!$this->sites->{$siteData->site_class}->isValidTitleURL($title)) {
				//Error is already logged via isValidTitleURL
				$success['status'] = 'Title URL is not valid';
				return $success;
			}
			if(!$this->sites->{$siteData->site_class}->isValidChapter($chapter)) {
				//Error is already logged via isValidChapter
				$success['status'] = 'Chapter URL is not valid';
				return $success;
			}

			//NOTE: If the title doesn't exist it will be created. This maybe isn't perfect, but it works for now.
			$titleID = $this->Tracker->getTitleID($title, (int) $siteData->id);
			if($titleID === 0) {
				//Something went wrong.
				log_message('error', "TitleID = 0 for {$title} @ {$siteData->id}");
				return $success;
			}

			//We need the series to be tracked
			$idCQuery = $this->db->select('id')
			                    ->where('user_id', $userID)
			                    ->where('title_id', $titleID)
			                    ->get('tracker_chapters');
			if($idCQuery->num_rows() > 0) {
				$idCQueryRow = $idCQuery->row();

				//Check if it is already favourited
				$idFQuery = $this->db->select('id')
				                    ->where('chapter_id', $idCQueryRow->id)
				                    ->where('chapter', $chapter)
				                    ->get('tracker_favourites');
				if($idFQuery->num_rows() > 0) {
					//Chapter is already favourited, so remove it from DB
					$idFQueryRow = $idFQuery->row();

					$isSuccess = (bool) $this->db->where('id', $idFQueryRow->id)
					                           ->delete('tracker_favourites');

					if($isSuccess) {
						$success = array(
							'status' => 'Unfavourited',
							'bool'   => TRUE
						);
						$this->History->userRemoveFavourite((int) $idCQueryRow->id, $chapter);
					}
				} else {
					//Chapter is not favourited, so add to DB.
					$isSuccess = (bool) $this->db->insert('tracker_favourites', [
						'chapter_id'      => $idCQueryRow->id,
						'chapter'         => $chapter,
						'updated_at'      => date('Y-m-d H:i:s')
					]);

					if($isSuccess) {
						$success = array(
							'status' => 'Favourited',
							'bool'   => TRUE
						);
						$this->History->userAddFavourite((int) $idCQueryRow->id, $chapter);
					}
				}
			} else {
				$success['status'] = 'Series needs to be tracked before we can favourite chapters';
			}
		}
		return $success;
	}
	public function getFavourites(int $page) : array {
		$rowsPerPage = 50;
		$query = $this->db
			->select('SQL_CALC_FOUND_ROWS
			          tt.title, tt.title_url,
			          ts.site, ts.site_class,
			          tf.chapter, tf.updated_at', FALSE)
			->from('tracker_favourites AS tf')
			->join('tracker_chapters AS tc', 'tf.chapter_id = tc.id', 'left')
			->join('tracker_titles AS tt', 'tc.title_id = tt.id', 'left')
			->join('tracker_sites AS ts', 'tt.site_id = ts.id', 'left')
			->where('tc.user_id', $this->User->id) //CHECK: Is this inefficient? Would it be better to have a user_id column in tracker_favourites?
			->order_by('tf.id DESC')
			->limit($rowsPerPage, ($rowsPerPage * ($page - 1)))
			->get();

		$arr = ['rows' => [], 'totalPages' => 1];
		if($query->num_rows() > 0) {
			foreach($query->result() as $row) {
				$arrRow = [];

				$arrRow['updated_at'] = $row->updated_at;
				$arrRow['title']      = $row->title;
				$arrRow['title_url']  = $this->Tracker->sites->{$row->site_class}->getFullTitleURL($row->title_url);

				$arrRow['site'] = $row->site;
				$arrRow['site_sprite'] = str_replace('.', '-', $row->site);

				$chapterData = $this->Tracker->sites->{$row->site_class}->getChapterData($row->title_url, $row->chapter);
				$arrRow['chapter'] = "<a href=\"{$chapterData['url']}\">{$chapterData['number']}</a>";
				$arr['rows'][] = $arrRow;
			}
			$arr['totalPages'] = ceil($this->db->query('SELECT FOUND_ROWS() count;')->row()->count / $rowsPerPage);
		}
		return $arr;

	}

	public function getSites() : array {
		$query = $this->db->select('*')
		                  ->from('tracker_sites')
		                  ->where('status', 'enabled')
		                  ->get();

		return $query->result_array();
	}

	public function getUsedCategories(int $userID) : array {
		$query = $this->db->distinct()
		                  ->select('category')
		                  ->from('tracker_chapters')
		                  ->where('tracker_chapters.active', 'Y')
		                  ->where('user_id', $userID)
		                  ->get();

		return array_column($query->result_array(), 'category');
	}

	public function getStats() : array {
		if(!($stats = $this->cache->get('site_stats'))) {
			$stats = array();

			//CHECK: Is it possible to merge some of these queries?
			$queryUsers = $this->db->select([
			                            'COUNT(*) AS total_users',
			                            'SUM(CASE WHEN api_key IS NOT NULL THEN 1 ELSE 0 END) AS validated_users',
			                            'SUM(CASE WHEN (api_key IS NOT NULL AND from_unixtime(last_login) > DATE_SUB(NOW(), INTERVAL 7 DAY)) THEN 1 ELSE 0 END) AS active_users'
			                       ], FALSE)
			                       ->from('auth_users')
			                       ->get();
			$stats = array_merge($stats, $queryUsers->result_array()[0]);

			$queryCounts = $this->db->select([
			                             'tracker_titles.title',
			                             'COUNT(tracker_chapters.title_id) AS count'
			                        ], FALSE)
			                        ->from('tracker_chapters')
			                        ->join('tracker_titles', 'tracker_titles.id = tracker_chapters.title_id', 'left')
			                        ->group_by('tracker_chapters.title_id')
			                        ->having('count > 1')
			                        ->order_by('count DESC')
			                        ->get();
			$stats['titles_tracked_more'] = count($queryCounts->result_array());
			$stats['top_title_name']  = $queryCounts->result_array()[0]['title'] ?? 'N/A';
			$stats['top_title_count'] = $queryCounts->result_array()[0]['count'] ?? 'N/A';

			$queryTitles = $this->db->select([
			                             'COUNT(DISTINCT tracker_titles.id) AS total_titles',
			                             'COUNT(DISTINCT tracker_titles.site_id) AS total_sites',
			                             'SUM(CASE WHEN from_unixtime(auth_users.last_login) > DATE_SUB(NOW(), INTERVAL 120 HOUR) IS NOT NULL THEN 0 ELSE 1 END) AS inactive_titles',
			                             'SUM(CASE WHEN (tracker_titles.last_updated > DATE_SUB(NOW(), INTERVAL 24 HOUR)) THEN 1 ELSE 0 END) AS updated_titles'
			                        ], FALSE)
			                        ->from('tracker_titles')
			                        ->join('tracker_sites', 'tracker_sites.id = tracker_titles.site_id', 'left')
			                        ->join('tracker_chapters', 'tracker_titles.id = tracker_chapters.title_id', 'left')
			                        ->join('auth_users', 'tracker_chapters.user_id = auth_users.id', 'left')
			                        ->get();
			$stats = array_merge($stats, $queryTitles->result_array()[0]);

			$querySites = $this->db->select([
			                           'tracker_sites.site',
			                           'COUNT(*) AS count'
			                       ], FALSE)
			                       ->from('tracker_titles')
			                       ->join('tracker_sites', 'tracker_sites.id = tracker_titles.site_id', 'left')
			                       ->group_by('tracker_titles.site_id')
			                       ->order_by('count DESC')
			                       ->limit(3)
			                       ->get();
			$querySitesResult = $querySites->result_array();
			$stats['rank1_site']       = $querySitesResult[0]['site'];
			$stats['rank1_site_count'] = $querySitesResult[0]['count'];
			$stats['rank2_site']       = $querySitesResult[1]['site'];
			$stats['rank2_site_count'] = $querySitesResult[1]['count'];
			$stats['rank3_site']       = $querySitesResult[2]['site'];
			$stats['rank3_site_count'] = $querySitesResult[2]['count'];

			$queryTitlesU = $this->db->select([
			                             'COUNT(*) AS title_updated_count'
			                         ], FALSE)
			                         ->from('tracker_titles_history')
			                         ->get();
			$stats = array_merge($stats, $queryTitlesU->result_array()[0]);

			$queryUsersU = $this->db->select([
			                            'COUNT(*) AS user_updated_count'
			                        ], FALSE)
			                        ->from('tracker_user_history')
			                        ->get();
			$stats = array_merge($stats, $queryUsersU->result_array()[0]);

			$stats['live_time'] = timespan(/*2016-09-10T03:17:19*/ 1473477439, time(), 2);

			$this->cache->save('site_stats', $stats, 3600); //Cache for an hour
		}

		return $stats;
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
