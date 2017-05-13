<?php defined('BASEPATH') OR exit('No direct script access allowed');

class Migration_Tracker_Add_MAL_id extends CI_Migration {
	public function __construct() {
		parent::__construct();
		$this->load->dbforge();
	}

	public function up() {
		$fields = array(
			'mal_id' => array(
				'type'      => 'MEDIUMINT',
				'constraint' => '7',
				'null'       => TRUE,
				'unsigned'   => TRUE,

				//'default'    => NULL
			)
		);

		$this->dbforge->add_column('tracker_titles', $fields, 'status');
		$this->dbforge->add_column('tracker_chapters', $fields, 'ignore_chapter');
	}

	public function down() {
		$this->dbforge->drop_column('tracker_titles',   'mal_id');
		$this->dbforge->drop_column('tracker_chapters', 'mal_id');

	}
}
