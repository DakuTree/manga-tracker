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
		}
		return (bool) $success;
	}

	/*** USER HISTORY ***/
	public function userAddTitle(int $chapterID, string $chapter, string $category) : bool {
		$success = $this->db->insert('tracker_titles_history', [
			'chapter_id'  => $chapterID,

			'type'        => '1',
			'custom1'     => $chapter,
			'custom2'     => $category,

			'updated_at'  => date('Y-m-d H:i:s')
		]);

		return $success;
	}
	public function userUpdateTitle(int $chapterID, string $old_chapter, string $new_chapter) : bool {
		$success = $this->db->insert('tracker_titles_history', [
			'chapter_id'  => $chapterID,

			'type'        => '2',
			'custom1'     => $old_chapter,
			'custom2'     => $new_chapter,

			'updated_at'  => date('Y-m-d H:i:s')
		]);

		return $success;
	}
	public function userRemoveTitle(int $chapterID) : bool {
		$success = $this->db->insert('tracker_titles_history', [
			'chapter_id'  => $chapterID,

			'type'        => '3',

			'updated_at'  => date('Y-m-d H:i:s')
		]);

		return $success;
	}
	public function userUpdateTags(int $chapterID, string/*|null*/ $old_tags, string $new_tags) : bool {
		$success = $this->db->insert('tracker_titles_history', [
			'chapter_id'  => $chapterID,

			'type'        => '4',
			'custom1'     => $old_tags,
			'custom2'     => $new_tags,

			'updated_at'  => date('Y-m-d H:i:s')
		]);

		return $success;
	}
	public function userUpdateCategory(int $chapterID, string $old_category, string $new_category) : bool {
		$success = $this->db->insert('tracker_titles_history', [
			'chapter_id'  => $chapterID,

			'type'        => '5',
			'custom1'     => $old_category,
			'custom2'     => $new_category,

			'updated_at'  => date('Y-m-d H:i:s')
		]);

		return $success;
	}
}
