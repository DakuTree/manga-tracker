<?php defined('BASEPATH') OR exit('No direct script access allowed');

class Migration_Tracker_add_category extends CI_Migration {
	public function __construct() {
		parent::__construct();
		$this->load->dbforge();
	}

	public function up() {
		$fields = array(
			'category' => array(
				'type'      => 'ENUM("reading", "on-hold", "plan-to-read", "custom1", "custom2", "custom3")',
				'null'      => FALSE,
				'default'   => 'reading'
			),
		);
		$this->dbforge->add_key('category');
		$this->dbforge->add_column('tracker_chapters', $fields, 'tags');
	}

	public function down() {
		$this->dbforge->drop_column('tracker_chapters', 'category');
	}
}
