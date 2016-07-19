<?php defined('BASEPATH') OR exit('No direct script access allowed');

class Migration_Tracker_add_tags extends CI_Migration {
	public function __construct() {
		parent::__construct();
		$this->load->dbforge();
	}

	public function up() {
		$fields = array(
			'tags' => array(
				'type'       => 'VARCHAR',
				'constraint' => '255',
				'null'      => FALSE,
				'default'   => ''
			),
		);
		$this->dbforge->add_column('tracker_chapters', $fields, 'current_chapter');
	}

	public function down() {
		$this->dbforge->drop_column('tracker_chapters', 'tags');
	}
}
