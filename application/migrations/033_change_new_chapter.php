<?php defined('BASEPATH') OR exit('No direct script access allowed');

class Migration_Change_New_Chapter extends CI_Migration {
	public function __construct() {
		parent::__construct();
		$this->load->dbforge();
	}

	public function up() {
		$this->db->query('ALTER TABLE `tracker_titles_history` CHANGE `new_chapter` `new_chapter` VARCHAR(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NULL');

	}

	public function down() {}
}
