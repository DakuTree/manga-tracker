<?php defined('BASEPATH') OR exit('No direct script access allowed');

class Migration_Change_Notices_Type extends CI_Migration {
	public function __construct() {
		parent::__construct();
		$this->load->dbforge();
	}

	public function up() {
		$this->db->query('ALTER TABLE `tracker_notices` CHANGE `notice` `notice` TEXT CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL');

	}

	public function down() {}
}
