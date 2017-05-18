<?php defined('BASEPATH') OR exit('No direct script access allowed');

class Migration_Tracker_Add_Followed extends CI_Migration {
	public function __construct() {
		parent::__construct();
		$this->load->dbforge();
	}

	public function up() {
		$fields = array(
			'followed' => array(
				'type'      => 'ENUM("Y", "N")',
				'null'      => FALSE,
				'default'   => 'N'
			),
		);
		$this->dbforge->add_column('tracker_titles', $fields, 'status');

		$this->db->query("
			ALTER TABLE `tracker_titles` ADD INDEX `followed` (`followed`)
		");
	}

	public function down() {
		$this->dbforge->drop_column('tracker_titles', 'followed');
	}
}
