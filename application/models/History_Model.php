<?php declare(strict_types=1); defined('BASEPATH') OR exit('No direct script access allowed');

class History_Model extends CI_Model {
	public function __construct() {
		parent::__construct();

		$this->load->database();
	}

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

	public function getCurrentChapter(int $titleID) : string {
		$query = $this->db->select('latest_chapter')
		                  ->from('tracker_titles')
		                  ->where('id', $titleID)
		                  ->get();

		return $query->row()->latest_chapter;
	}
}
