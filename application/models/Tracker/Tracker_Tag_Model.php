<?php declare(strict_types=1); defined('BASEPATH') OR exit('No direct script access allowed');

class Tracker_Tag_Model extends Tracker_Base_Model {
	public function __construct() {
		parent::__construct();
	}

	public function updateByID(int $userID, int $chapterID, string $tag_string) : bool {
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

	public function getMalID(int $userID, int $titleID) : ?int {
		$query = $this->db->select('tags')
		                    ->where('user_id', $userID)
		                    ->where('title_id', $titleID)
		                    ->get('tracker_chapters');

		$malID = NULL;
		if($query->num_rows() > 0 && ($tag_string = $query->row()->tags)) {
			$arr   = preg_grep('/^mal:([0-9]+)$/', explode(',', $tag_string));
			$malID = (int) explode(':', $arr[0])[1];
		}
		return $malID;
	}
}
