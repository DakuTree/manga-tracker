<?php defined('BASEPATH') OR exit('No direct script access allowed');

class Migration_Tracker_Add_Failed_Checks extends CI_Migration {
	public function __construct() {
		parent::__construct();
		$this->load->dbforge();
	}

	public function up() {
		$fields = array(
			'failed_checks' => array(
				'type'       => 'MEDIUMINT',
				'constraint' => '7',
				'default'    => '0'
			),
		);
		$this->dbforge->add_column('tracker_titles', $fields, 'last_checked');
	}

	public function down() {
		$this->dbforge->drop_column('tracker_titles', 'last_checked');
	}
}
