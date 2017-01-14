<?php declare(strict_types=1); defined('BASEPATH') OR exit('No direct script access allowed');

class Tracker_List_Model extends Tracker_Base_Model {
	public function __construct() {
		parent::__construct();
	}

	public function get() {
		$query = $this->db
			->select('tracker_chapters.*,
			          tracker_titles.site_id, tracker_titles.title, tracker_titles.title_url, tracker_titles.latest_chapter, tracker_titles.last_updated AS title_last_updated, tracker_titles.status AS title_status, tracker_titles.last_checked > DATE_SUB(NOW(), INTERVAL 1 WEEK) AS title_active,
			          tracker_sites.site, tracker_sites.site_class, tracker_sites.status AS site_status')
			->from('tracker_chapters')
			->join('tracker_titles', 'tracker_chapters.title_id = tracker_titles.id', 'left')
			->join('tracker_sites', 'tracker_sites.id = tracker_titles.site_id', 'left')
			->where('tracker_chapters.user_id', $this->User->id)
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


	public function update(int $userID, string $site, string $title, string $chapter) : bool {
		$success = FALSE;
		if($siteData = $this->Tracker->title->getSiteDataFromURL($site)) {
			//Validate user input
			if(!$this->sites->{$siteData->site_class}) {
				log_message('error', "{$siteData->site_class} Class doesn't exist?");
				return FALSE;
			}
			if(!$this->sites->{$siteData->site_class}->isValidTitleURL($title)) {
				//Error is already logged via isValidTitleURL
				return FALSE;
			}
			if(!$this->sites->{$siteData->site_class}->isValidChapter($chapter)) {
				//Error is already logged via isValidChapter
				return FALSE;
			}

			//NOTE: If the title doesn't exist it will be created. This maybe isn't perfect, but it works for now.
			$titleID = $this->Tracker->title->getID($title, (int) $siteData->id);
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
	public function updateByID(int $userID, int $chapterID, string $chapter) : bool {
		$success = (bool) $this->db->set(['current_chapter' => $chapter, 'active' => 'Y', 'last_updated' => NULL])
		                           ->where('user_id', $userID)
		                           ->where('id', $chapterID)
		                           ->update('tracker_chapters');

		if($success) {
			$this->History->userUpdateTitle($chapterID, $chapter);
		}
		return  $success;
	}

	public function deleteByID(int $userID, int $chapterID) {
		//Series are not fully deleted, they are just marked as inactive as to hide them from the user.
		//This is to allow user history to function properly.

		$success = $this->db->set(['active' => 'N', 'last_updated' => NULL])
		                    ->where('user_id', $userID)
		                    ->where('id', $chapterID)
		                    ->update('tracker_chapters');

		return (bool) $success;
	}
	public function deleteByIDList(array $idList) : array {
		/*
		 * 0 = Success
		 * 1 = Invalid IDs
		 */
		$status = ['code' => 0];

		foreach($idList as $id) {
			if(!(ctype_digit($id) && $this->deleteByID($this->User->id, (int) $id))) {
				$status['code'] = 1;
			} else {
				//Delete was successful, update history too.
				$this->History->userRemoveTitle((int) $id);
			}
		}

		return $status;
	}
}
