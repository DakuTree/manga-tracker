<?php defined('BASEPATH') OR exit('No direct script access allowed');

class Migration_Tracker_add_last_checked extends CI_Migration {
	public function __construct() {
		parent::__construct();
		$this->load->dbforge();
	}

	public function up() {
		$fields = array(
			//'last_checked' => array(
			//	'type' => 'TIMESTAMP',
			//	'null' => FALSE,
			//	//'default'   => 'CURRENT_TIMESTAMP',
			//	//'on_update' => '' //This is auto-added by CI, but we need to set manually.
			//),
			//The above format doesn't seem to work nicely here.
			'`last_checked` TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP AFTER `last_updated`',
		);
		//$this->dbforge->add_key('last_checked');
		$this->dbforge->add_column('tracker_titles', $fields, 'last_updated');

		$this->db->query("
			ALTER TABLE `tracker_titles` ADD INDEX `last_checked` (`last_checked`)
		");

		$this->db->query("
			DELIMITER ;;
			CREATE TRIGGER `update_last_updated` BEFORE UPDATE ON `tracker_titles` FOR EACH ROW
			IF NOT (NEW.latest_chapter <=> OLD.latest_chapter) THEN
				SET NEW.last_updated = CURRENT_TIMESTAMP;
			END IF;;
			DELIMITER ;
		");
	}

	public function down() {
		$this->dbforge->drop_column('tracker_titles', 'last_checked');
	}
}
