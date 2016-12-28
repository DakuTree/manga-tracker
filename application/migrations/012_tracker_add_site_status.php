<?php defined('BASEPATH') OR exit('No direct script access allowed');

class Migration_Tracker_Add_Site_Status extends CI_Migration {
	public function __construct() {
		parent::__construct();
		$this->load->dbforge();
	}

	public function up() {
		$fields = array(
			'status' => array(
				'type'      => 'ENUM("enabled", "disabled", "other")',
				'null'      => FALSE,
				'default'   => 'enabled'
			),
		);
		//$this->dbforge->add_key('status');
		$this->dbforge->add_column('tracker_sites', $fields, 'site_class');

		$this->db->query("
			ALTER TABLE `tracker_sites` ADD INDEX `status` (`status`)
		");
	}

	public function down() {
		$this->dbforge->drop_column('tracker_sites', 'status');
	}
}
