<?php defined('BASEPATH') OR exit('No direct script access allowed');

class Migration_Tracker_add_ignore_chapter extends CI_Migration {
	public function __construct() {
		parent::__construct();
		$this->load->dbforge();
	}

	public function up() {
		$fields = array(
			'ignore_chapter' => array(
				'type'       => 'VARCHAR',
				'constraint' => '255',
				'null'       => TRUE
			),
		);
		$this->dbforge->add_column('tracker_chapters', $fields, 'category');
	}

	public function down() {
		$this->dbforge->drop_column('tracker_chapters', 'ignore_chapter');
	}
}
