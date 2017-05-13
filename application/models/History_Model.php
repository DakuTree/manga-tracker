<?php declare(strict_types=1); defined('BASEPATH') OR exit('No direct script access allowed');

class History_Model extends CI_Model {
	public function __construct() {
		parent::__construct();

		$this->load->database();
	}

	/*** TITLE HISTORY ***/
	public function updateTitleHistory(int $titleID, $oldChapter, string $newChapter, string $newChapterTimestamp) {
		$success = TRUE;
		if($oldChapter !== $newChapter) {
			$success = $this->db->insert('tracker_titles_history', [
				'title_id'    => $titleID,

				'old_chapter' => $oldChapter,
				'new_chapter' => $newChapter,

				'updated_at'  => $newChapterTimestamp
			]);
			$this->db->cache_delete('history', (string) $titleID);
		}
		return (bool) $success;
	}

	public function getTitleHistory(int $titleID, int $page = 1) : array {
		$rowsPerPage = 50;
		$this->db->cache_on();
		$query = $this->db
			->select('SQL_CALC_FOUND_ROWS
			          tt.title_url,
			          ts.site_class,
			          tth.updated_at, tth.new_chapter', FALSE)
			->from('tracker_titles_history AS tth')
			->join('tracker_titles AS tt', 'tth.title_id = tt.id', 'left')
			->join('tracker_sites AS ts', 'tt.site_id = ts.id', 'left')
			->where('tt.id', $titleID)
			->order_by('tth.id DESC')
			->limit($rowsPerPage, ($rowsPerPage * ($page - 1)))
			->get();
		$this->db->cache_off();

		$arr = ['rows' => [], 'totalPages' => 1];
		if($query->num_rows() > 0) {
			foreach($query->result() as $row) {
				$arrRow = [];

				$arrRow['updated_at']  = $row->updated_at;

				$newChapterData = $this->Tracker->sites->{$row->site_class}->getChapterData($row->title_url, $row->new_chapter);
				$arrRow['new_chapter']      = "<a href=\"{$newChapterData['url']}\">{$newChapterData['number']}</a>";
				$arrRow['new_chapter_full'] = $row->new_chapter;

				$arr['rows'][] = $arrRow;
			}
			$arr['totalPages'] = ceil($this->db->query('SELECT FOUND_ROWS() count;')->row()->count / $rowsPerPage);
		}
		return $arr;
	}

	/*** USER HISTORY ***/
	/*
	 * --User history types--
	 * 1: Title added
	 * 2: Title updated
	 * 3: Title removed
	 * 4: Tags updated
	 * 5: Category updated
	 * 6: Favourite added
	 * 7: Favourite removed
	 * 8: Title ignored
	 */

	public function userAddTitle(int $chapterID, string $chapter, string $category) : bool {
		$success = $this->db->insert('tracker_user_history', [
			'chapter_id'  => $chapterID,

			'type'        => '1',
			'custom1'     => $chapter,
			'custom2'     => $category,

			'updated_at'  => date('Y-m-d H:i:s')
		]);

		return $success;
	}
	public function userUpdateTitle(int $chapterID, string $new_chapter) : bool {
		$success = $this->db->insert('tracker_user_history', [
			'chapter_id'  => $chapterID,

			'type'        => '2',
			'custom1'     => $new_chapter,

			'updated_at'  => date('Y-m-d H:i:s')
		]);

		return $success;
	}
	public function userRemoveTitle(int $chapterID) : bool {
		$success = $this->db->insert('tracker_user_history', [
			'chapter_id'  => $chapterID,

			'type'        => '3',

			'updated_at'  => date('Y-m-d H:i:s')
		]);

		return $success;
	}
	public function userUpdateTags(int $chapterID, string $new_tags) : bool {
		$success = $this->db->insert('tracker_user_history', [
			'chapter_id'  => $chapterID,

			'type'        => '4',
			'custom1'     => $new_tags,

			'updated_at'  => date('Y-m-d H:i:s')
		]);

		return $success;
	}
	public function userUpdateCategory(int $chapterID, string $new_category) : bool {
		$success = $this->db->insert('tracker_user_history', [
			'chapter_id'  => $chapterID,

			'type'        => '5',
			'custom1'     => $new_category,

			'updated_at'  => date('Y-m-d H:i:s')
		]);

		return $success;
	}
	public function userAddFavourite(int $chapterID, string $chapter) : bool {
		$success = $this->db->insert('tracker_user_history', [
			'chapter_id'  => $chapterID,

			'type'        => '6',
			'custom1'     => $chapter,

			'updated_at'  => date('Y-m-d H:i:s')
		]);

		return $success;
	}
	public function userRemoveFavourite(int $chapterID, string $chapter) : bool {
		$success = $this->db->insert('tracker_user_history', [
			'chapter_id'  => $chapterID,

			'type'        => '7',
			'custom1'     => $chapter,

			'updated_at'  => date('Y-m-d H:i:s')
		]);

		return $success;
	}
	public function userIgnoreTitle(int $chapterID, string $new_chapter) : bool {
		$success = $this->db->insert('tracker_user_history', [
			'chapter_id'  => $chapterID,

			'type'        => '8',
			'custom1'     => $new_chapter,

			'updated_at'  => date('Y-m-d H:i:s')
		]);

		return $success;
	}
	public function userSetMalID(int $chapterID, ?int $malID) : bool {
		$success = $this->db->insert('tracker_user_history', [
			'chapter_id'  => $chapterID,

			'type'        => '9',
			'custom1'     => $malID,

			'updated_at'  => date('Y-m-d H:i:s')
		]);

		return $success;
	}

	public function userGetHistory(int $page) : array {
		$rowsPerPage = 50;
		$query = $this->db
			->select('SQL_CALC_FOUND_ROWS
			          tt.title, tt.title_url,
			          ts.site, ts.site_class,
			          tuh.type, tuh.custom1, tuh.custom2, tuh.custom3, tuh.updated_at', FALSE)
			->from('tracker_user_history AS tuh')
			->join('tracker_chapters AS tc', 'tuh.chapter_id = tc.id', 'left')
			->join('tracker_titles AS tt', 'tc.title_id = tt.id', 'left')
			->join('tracker_sites AS ts', 'tt.site_id = ts.id', 'left')
			->where('tc.user_id', $this->User->id)
			->order_by('tuh.id DESC')
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

				switch($row->type) {
					case 1:
						$chapterData = $this->Tracker->sites->{$row->site_class}->getChapterData($row->title_url, $row->custom1);
						$arrRow['status'] = "Series added at '<a href=\"{$chapterData['url']}\">{$chapterData['number']}</a>' to category '{$row->custom2}'";
						break;

					case 2:
						$chapterData = $this->Tracker->sites->{$row->site_class}->getChapterData($row->title_url, $row->custom1);
						$arrRow['status'] = "Chapter updated to '<a href=\"{$chapterData['url']}\">{$chapterData['number']}</a>'";
						break;

					case 3:
						$arrRow['status'] = "Series removed";
						break;

					case 4:
						$arrRow['status'] = "Tags set to '{$row->custom1}'";
						break;

					case 5:
						$arrRow['status'] = "Category set to '{$row->custom1}'";
						break;

					case 6:
						$chapterData = $this->Tracker->sites->{$row->site_class}->getChapterData($row->title_url, $row->custom1);
						$arrRow['status'] = "Favourited '<a href=\"{$chapterData['url']}\">{$chapterData['number']}</a>'";
						break;

					case 7:
						$chapterData = $this->Tracker->sites->{$row->site_class}->getChapterData($row->title_url, $row->custom1);
						$arrRow['status'] = "Unfavourited '<a href=\"{$chapterData['url']}\">{$chapterData['number']}</a>'";
						break;

					case 8:
						$chapterData = $this->Tracker->sites->{$row->site_class}->getChapterData($row->title_url, $row->custom1);
						$arrRow['status'] = "Chapter ignored: '<a href=\"{$chapterData['url']}\">{$chapterData['number']}</a>'";
						break;

					case 9:
						if(!is_null($row->custom1)) {
							$arrRow['status'] = "MAL ID to '{$row->custom1}'";
						} else {
							$arrRow['status'] = "MAL ID removed";
						}
						break;

					default:
						$arrRow['status'] = "Something went wrong!";
						break;
				}
				$arr['rows'][] = $arrRow;
			}
			$arr['totalPages'] = ceil($this->db->query('SELECT FOUND_ROWS() count;')->row()->count / $rowsPerPage);
		}
		return $arr;
	}

	public function userGetHistoryAll() : array {
		$rowsPerPage = 50;
		$query = $this->db
			->select('SQL_CALC_FOUND_ROWS
			          tt.title, tt.title_url,
			          ts.site, ts.site_class,
			          tuh.type, tuh.custom1, tuh.custom2, tuh.custom3, tuh.updated_at', FALSE)
			->from('tracker_user_history AS tuh')
			->join('tracker_chapters AS tc', 'tuh.chapter_id = tc.id', 'left')
			->join('tracker_titles AS tt', 'tc.title_id = tt.id', 'left')
			->join('tracker_sites AS ts', 'tt.site_id = ts.id', 'left')
			->where('tc.user_id', $this->User->id)
			->order_by('tuh.id DESC')
			->get();

		$arr = [];
		if($query->num_rows() > 0) {
			foreach($query->result() as $row) {
				$arrRow = [];

				$arrRow['updated_at'] = $row->updated_at;
				$arrRow['title']      = $row->title;
				$arrRow['title_url']  = $this->Tracker->sites->{$row->site_class}->getFullTitleURL($row->title_url);

				$arrRow['site'] = $row->site;

				switch($row->type) {
					case 1:
						$chapterData = $this->Tracker->sites->{$row->site_class}->getChapterData($row->title_url, $row->custom1);
						$arrRow['status'] = "Series added at '<a href=\"{$chapterData['url']}\">{$chapterData['number']}</a>' to category '{$row->custom2}'";
						break;

					case 2:
						$chapterData = $this->Tracker->sites->{$row->site_class}->getChapterData($row->title_url, $row->custom1);
						$arrRow['status'] = "Chapter updated to '<a href=\"{$chapterData['url']}\">{$chapterData['number']}</a>'";
						break;

					case 3:
						$arrRow['status'] = "Series removed";
						break;

					case 4:
						$arrRow['status'] = "Tags set to '{$row->custom1}'";
						break;

					case 5:
						$arrRow['status'] = "Category set to '{$row->custom1}'";
						break;

					case 6:
						$chapterData = $this->Tracker->sites->{$row->site_class}->getChapterData($row->title_url, $row->custom1);
						$arrRow['status'] = "Favourited '<a href=\"{$chapterData['url']}\">{$chapterData['number']}</a>'";
						break;

					case 7:
						$chapterData = $this->Tracker->sites->{$row->site_class}->getChapterData($row->title_url, $row->custom1);
						$arrRow['status'] = "Unfavourited '<a href=\"{$chapterData['url']}\">{$chapterData['number']}</a>'";
						break;

					case 8:
						$chapterData = $this->Tracker->sites->{$row->site_class}->getChapterData($row->title_url, $row->custom1);
						$arrRow['status'] = "Chapter ignored: '<a href=\"{$chapterData['url']}\">{$chapterData['number']}</a>'";
						break;

					default:
						$arrRow['status'] = "Something went wrong!";
						break;
				}
				$arr[] = $arrRow;
			}
		}
		return $arr;
	}
}
