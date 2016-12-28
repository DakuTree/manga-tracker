<?php defined('BASEPATH') OR exit('No direct script access allowed');

class Migration_Tracker_Add_Active extends CI_Migration {
	public function __construct() {
		parent::__construct();
		$this->load->dbforge();
	}

	public function up() {
		$fields = array(
			'active' => array(
				'type'      => 'ENUM("Y", "N")',
				'null'      => FALSE,
				'default'   => 'Y'
			),
		);
		//$this->dbforge->add_key('active');
		$this->dbforge->add_column('tracker_chapters', $fields, 'category');

		$this->db->query("
			ALTER TABLE `tracker_chapters` ADD INDEX `active` (`active`)
		");
	}

	public function down() {
		$this->dbforge->drop_column('tracker_chapters', 'active');
	}
}
