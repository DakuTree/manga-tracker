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
}
