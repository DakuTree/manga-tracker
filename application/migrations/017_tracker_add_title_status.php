<?php defined('BASEPATH') OR exit('No direct script access allowed');

class Migration_Tracker_Add_Title_Status extends CI_Migration {
	public function __construct() {
		parent::__construct();
		$this->load->dbforge();
	}

	public function up() {
		$fields = array(
			'status' => array(
				'type'           => 'TINYINT',
				'constraint'     => '1',
				'unsigned'       => TRUE,
				'null'           => FALSE,
				'default'        => '0'
			)
		);
		//$this->dbforge->add_key('status');
		if($this->dbforge->add_column('tracker_titles', $fields, 'complete')) {
			$this->db->query("
				ALTER TABLE `tracker_titles` ADD INDEX `status` (`status`)
			");

			/** @noinspection SqlResolve */
			if($this->db->query('
				UPDATE `tracker_titles`
				SET `status` = 1
				WHERE `complete` = "Y"'
			)) {
				$this->dbforge->drop_column('tracker_titles', 'complete');
			};
		}
	}

	public function down() {
		$this->dbforge->drop_column('tracker_titles', 'status');

		//NOTE: We can't really have a fallback for re-adding the complete column.
	}
}
