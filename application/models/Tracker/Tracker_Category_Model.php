<?php declare(strict_types=1); defined('BASEPATH') OR exit('No direct script access allowed');

class Tracker_Category_Model extends Tracker_Base_Model {
	public function __construct() {
		parent::__construct();
	}

	public function setByID(int $userID, int $chapterID, string $category) : bool {
		$success = $this->db->set(['category' => $category, 'active' => 'Y', 'last_updated' => NULL])
		                    ->where('user_id', $userID)
		                    ->where('id', $chapterID)
		                    ->update('tracker_chapters');

		return (bool) $success;
	}

	public function setByIDList(array $idList, string $category) : array {
		/*
		 * 0 = Success
		 * 1 = Invalid IDs
		 * 2 = Invalid category
		 */
		$status = ['code' => 0];

		if(in_array($category, array_keys($this->enabledCategories))) {
			foreach($idList as $id) {
				if(!(ctype_digit($id) && $this->setByID($this->User->id, (int) $id, $category))) {
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

	public function getUsed(int $userID) : array {
		$query = $this->db->distinct()
		                  ->select('category')
		                  ->from('tracker_chapters')
		                  ->where('tracker_chapters.active', 'Y')
		                  ->where('user_id', $userID)
		                  ->get();

		return array_column($query->result_array(), 'category');
	}
}
