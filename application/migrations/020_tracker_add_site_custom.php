<?php defined('BASEPATH') OR exit('No direct script access allowed');

class Migration_Tracker_Add_Site_Custom extends CI_Migration {
	public function __construct() {
		parent::__construct();
		$this->load->dbforge();
	}

	public function up() {
		$fields = array(
			'use_custom' => array(
				'type'      => 'ENUM("Y", "N")',
				'null'      => FALSE,
				'default'   => 'N'
			),
		);
		$this->dbforge->add_column('tracker_sites', $fields, 'status');

		$this->db->query("
			ALTER TABLE `tracker_sites` ADD INDEX `use_custom` (`use_custom`)
		");
	}

	public function down() {
		$this->dbforge->drop_column('tracker_sites', 'use_custom');
	}
}
