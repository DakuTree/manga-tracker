<?php declare(strict_types=1); defined('BASEPATH') OR exit('No direct script access allowed');

class Tracker_List_Model extends Tracker_Base_Model {
	public function __construct() {
		parent::__construct();
	}

	public function get(?int $userID = NULL) {
		$userID = (is_null($userID) ? (int) $this->User->id : $userID);

		$query = $this->db
			->select('tracker_chapters.*, CONVERT_TZ(tracker_chapters.last_updated, @@session.time_zone, \'+00:00\') AS utc_last_updated,
			          tracker_titles.site_id, tracker_titles.title, tracker_titles.title_url, tracker_titles.latest_chapter, tracker_titles.last_updated AS title_last_updated, tracker_titles.status AS title_status, tracker_titles.mal_id AS title_mal_id, tracker_titles.last_checked > DATE_SUB(NOW(), INTERVAL 1 WEEK) AS title_active, tracker_titles.failed_checks AS title_failed_checks,
			          tracker_sites.site, tracker_sites.site_class, tracker_sites.status AS site_status', FALSE)
			->from('tracker_chapters')
			->join('tracker_titles', 'tracker_chapters.title_id = tracker_titles.id', 'left')
			->join('tracker_sites', 'tracker_sites.id = tracker_titles.site_id', 'left')
			->where('tracker_chapters.user_id', $userID)
			->where('tracker_chapters.active', 'Y')
			->get();

		$arr = ['series' => [], 'has_inactive' => FALSE, 'inactive_titles' => []];
		$enabledCategories = $this->getEnabledCategories($userID);
		foreach($enabledCategories as $category => $name) {
			$arr['series'][$category] = [
				'name'         => $name,
				'manga'        => [],
				'unread_count' => 0
			];
		}
		if($query->num_rows() > 0) {
			foreach ($query->result() as $row) {
				$is_unread = intval(($row->latest_chapter == $row->ignore_chapter) || ($row->latest_chapter == $row->current_chapter) ? '1' : '0');
				$arr['series'][$row->category]['unread_count'] = (($arr['series'][$row->category]['unread_count'] ?? 0) + !$is_unread);
				$data = [
					'id' => $row->id,
					'generated_current_data' => $this->sites->{$row->site_class}->getChapterData($row->title_url, $row->current_chapter),
					'generated_latest_data'  => $this->sites->{$row->site_class}->getChapterData($row->title_url, $row->latest_chapter),
					'generated_ignore_data'  => ($row->ignore_chapter ? $this->sites->{$row->site_class}->getChapterData($row->title_url, $row->ignore_chapter) : NULL),

					'full_title_url'        => $this->sites->{$row->site_class}->getFullTitleURL($row->title_url),

					'new_chapter_exists'    => $is_unread,
					'tag_list'              => $row->tags,
					'has_tags'              => !empty($row->tags),

					//TODO: We should have an option so chapter mal_id can take priority.
					'mal_id'                => $row->mal_id ?? $row->title_mal_id, //TODO: This should have an option
					'mal_type'              => (!is_null($row->mal_id) ? 'chapter' : (!is_null($row->title_mal_id) ? 'title' : 'none')),

					'last_updated' => $row->utc_last_updated,

					'title_data' => [
						'id'              => $row->title_id,
						'title'           => $row->title,
						'title_url'       => $row->title_url,
						'latest_chapter'  => $row->latest_chapter,
						'current_chapter' => $row->current_chapter,
						'ignore_chapter'  => $row->ignore_chapter,
						'last_updated'    => $row->title_last_updated,
						'status'          => (int) $row->title_status,
						'failed_checks'   => (int) $row->title_failed_checks,
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
				$data['mal_icon'] = (!is_null($data['mal_id']) ? ($data['mal_id'] !== '0' ? "<a href=\"https://myanimelist.net/manga/{$data['mal_id']}\" class=\"mal-link\"><i class=\"sprite-site sprite-myanimelist-net\" title=\"{$data['mal_id']}\"></i></a>" : "<a><i class=\"sprite-site sprite-myanimelist-net-none\" title=\"none\"></i></a>") : '');

				$arr['series'][$row->category]['manga'][] = $data;

				if(!$data['title_data']['active']) {
					if(!$arr['has_inactive']) $arr['has_inactive'] = TRUE;
					$arr['inactive_titles'][$data['full_title_url']] = $data['title_data']['title'];
				}
			}

			//FIXME: This is not good for speed, but we're kind of required to do this for UX purposes.
			//       Tablesorter has a weird sorting algorithm and has a delay before sorting which is why I'd like to avoid it.
			//FIXME: Is it possible to reduce duplication here without reducing speed?
			$sortOrder = $this->User_Options->get('list_sort_order', $userID);
			switch($this->User_Options->get('list_sort_type', $userID)) {
				case 'unread':
					foreach (array_keys($arr['series']) as $category) {
						usort($arr['series'][$category]['manga'], function ($a, $b) use($sortOrder) {
							$a_text = strtolower("{$a['new_chapter_exists']} - {$a['title_data']['title']}");
							$b_text = strtolower("{$b['new_chapter_exists']} - {$b['title_data']['title']}");

							if($sortOrder == 'asc') {
								return $a_text <=> $b_text;
							} else {
								return $b_text <=> $a_text;
							}
						});
					}
					break;

				case 'unread_latest':
					foreach (array_keys($arr['series']) as $category) {
						usort($arr['series'][$category]['manga'], function ($a, $b) use($sortOrder) {
							$a_text = $a['new_chapter_exists'];
							$b_text = $b['new_chapter_exists'];

							$a_text2 = new DateTime("{$a['title_data']['last_updated']}");
							$b_text2 = new DateTime("{$b['title_data']['last_updated']}");

							if($sortOrder == 'asc') {
								$unreadSort = ($a_text <=> $b_text);
								if($unreadSort) return $unreadSort;
								return $a_text2 <=> $b_text2;
							} else {
								$unreadSort = ($a_text <=> $b_text);
								if($unreadSort) return $unreadSort;
								return $b_text2 <=> $a_text2;
							}
						});
					}
					break;

				case 'alphabetical':
					foreach (array_keys($arr['series']) as $category) {
						usort($arr['series'][$category]['manga'], function($a, $b) use($sortOrder) {
							$a_text = strtolower("{$a['title_data']['title']}");
							$b_text = strtolower("{$b['title_data']['title']}");

							if($sortOrder == 'asc') {
								return $a_text <=> $b_text;
							} else {
								return $b_text <=> $a_text;
							}
						});
					}
					break;

				case 'my_status':
					foreach (array_keys($arr['series']) as $category) {
						usort($arr['series'][$category]['manga'], function($a, $b) use($sortOrder) {
							$a_text = strtolower("{$a['generated_current_data']['number']}");
							$b_text = strtolower("{$b['generated_current_data']['number']}");

							if($sortOrder == 'asc') {
								return $a_text <=> $b_text;
							} else {
								return $b_text <=> $a_text;
							}
						});
					}
					break;

				case 'latest':
					foreach (array_keys($arr['series']) as $category) {
						usort($arr['series'][$category]['manga'], function($a, $b) use($sortOrder) {
							$a_text = new DateTime("{$a['title_data']['last_updated']}");
							$b_text = new DateTime("{$b['title_data']['last_updated']}");

							if($sortOrder == 'asc') {
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

	public function update(int $userID, string $site, string $title, string $chapter, bool $active = TRUE, bool $returnTitleID = FALSE) {
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
				$success = (bool) $this->db->set(['current_chapter' => $chapter, 'active' => 'Y', 'last_updated' => NULL, 'ignore_chapter' => NULL])
				                           ->where('user_id', $userID)
				                           ->where('title_id', $titleID)
				                           ->update('tracker_chapters');

				if($success) {
					$idQueryRow = $idQuery->row();
					$this->History->userUpdateTitle((int) $idQueryRow->id, $chapter);
				}
			} else {
				$category = $this->User_Options->get('default_series_category', $userID);

				$success = (bool) $this->db->insert('tracker_chapters', [
					'user_id'         => $userID,
					'title_id'        => $titleID,
					'current_chapter' => $chapter,
					'category'        => $category,
					'active'          => ($active ? 'Y' : 'N')
				]);

				if($success) {
					$this->History->userAddTitle((int) $this->db->insert_id(), $chapter, $category);
				}
			}
		}
		return ($returnTitleID ? ($success ? ['id' => $titleID, 'chapter' => $this->sites->{$siteData->site_class}->getChapterData($title, $chapter)['number']] : $success) : $success);
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

	public function ignoreByID(int $userID, int $chapterID, string $chapter) : bool {
		$success = (bool) $this->db->set(['ignore_chapter' => $chapter, 'active' => 'Y', 'last_updated' => NULL])
		                           ->where('user_id', $userID)
		                           ->where('id', $chapterID)
		                           ->update('tracker_chapters');

		if($success) {
			$this->History->userIgnoreTitle($chapterID, $chapter);
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

	public function getMalID(int $userID, int $titleID) : ?array{
		$malIDArr = NULL;

		//NEW METHOD
		//TODO: OPTION, USE BACKEND MAL ID DB WHERE POSSIBLE (DEFAULT TRUE)

		$queryC = $this->db->select('mal_id')
		                   ->where('user_id', $userID)
		                   ->where('title_id', $titleID)
		                   ->get('tracker_chapters');

		if($queryC->num_rows() > 0 && ($rowC = $queryC->row())) {
			$malIDArr = [
				'id'   => ($rowC->mal_id == '0' ? 'none' : $rowC->mal_id),
				'type' => 'chapter'
			];
		} else {
			$queryT = $this->db->select('mal_id')
			                   ->where('title_id', $titleID)
			                   ->get('tracker_titles');

			if($queryT->num_rows() > 0 && ($rowT = $queryT->row())) {
				$malIDArr = [
					'id'   => ($rowT->mal_id == '0' ? 'none' : $rowT->mal_id),
					'type' => 'title'
				];
			}
		}

		//OLD METHOD
		//TODO: Remove after a few weeks!
		if(is_null($malIDArr)) {
			$queryC2 = $this->db->select('tags')
			                  ->where('user_id', $userID)
			                  ->where('title_id', $titleID)
			                  ->get('tracker_chapters');

			if($queryC2->num_rows() > 0 && ($tag_string = $queryC2->row()->tags) && !is_null($tag_string)) {
				$arr   = preg_grep('/^mal:([0-9]+|none)$/', explode(',', $tag_string));
				if(!empty($arr)) {
					$malIDArr = [
						'id'   => explode(':', $arr[0])[1],
						'type' => 'chapter'
					];
				}
			}
		}

		return $malIDArr;
	}
	public function setMalID(int $userID, int $chapterID, ?int $malID) : bool {
		//TODO: Handle NULL?
		$success = (bool) $this->db->set(['mal_id' => $malID, 'active' => 'Y', 'last_updated' => NULL])
		                           ->where('user_id', $userID)
		                           ->where('id', $chapterID)
		                           ->update('tracker_chapters');

		if($success) {
			//MAL id update was successful, update history
			$this->History->userSetMalID($chapterID, $malID);
		}
		return $success;
	}
}
