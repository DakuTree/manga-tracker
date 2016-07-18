<?php defined('BASEPATH') OR exit('No direct script access allowed');

class Migration_Tracker_add_complete extends CI_Migration {
	public function __construct() {
		parent::__construct();
		$this->load->dbforge();
	}

	public function up() {
		$fields = array(
			'complete' => array(
				'type'      => 'ENUM("Y", "N")',
				'null'      => FALSE,
				'default'   => 'N'
			),
		);
		$this->dbforge->add_key('complete');
		$this->dbforge->add_column('tracker_titles', $fields, 'latest_chapter');
	}

	public function down() {
		$this->dbforge->drop_column('tracker_titles', 'complete');
	}
}
