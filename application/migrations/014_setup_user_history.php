<?php defined('BASEPATH') OR exit('No direct script access allowed');

class Migration_Setup_User_History extends CI_Migration {
	public function __construct() {
		parent::__construct();
		$this->load->dbforge();
	}

	public function up() {
		$this->dbforge->add_field(array(
			'id' => array(
				'type'           => 'MEDIUMINT',
				'constraint'     => '8',
				'unsigned'       => TRUE,
				'auto_increment' => TRUE
			),
			'chapter_id' => array(
				'type'           => 'MEDIUMINT',
				'constraint'     => '8',
				'unsigned'       => TRUE
				//FOREIGN KEY
			),

			'type' => array(
				'type'       => 'TINYINT',
				'constraint' => '1',
				'unsigned'   => TRUE,
				'null'       => FALSE
			),
			'custom1' => array(
				'type'       => 'VARCHAR',
				'constraint' => '255',
				'null'       => TRUE
			),
			'custom2' => array(
				'type'       => 'VARCHAR',
				'constraint' => '255',
				'null'       => TRUE
			),
			'custom3' => array(
				'type'       => 'VARCHAR',
				'constraint' => '255',
				'null'       => TRUE
			),

			'updated_at' => array(
				//Despite not actually creating the field here (it's instead done below), we still need this here so a key can be created properly.
			//	'type'    => 'TIMESTAMP',
			//	'null'    => FALSE,
			//	'on_update' => FALSE
			)
		));
		$this->dbforge->add_field('updated_at TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP'); //CI is annoying and auto-appends ON UPDATE which we don't want.
		$this->dbforge->add_key('id', TRUE);
		$this->dbforge->add_key('chapter_id');
		$this->dbforge->add_key('type');
		$this->dbforge->add_key('updated_at');
		$this->dbforge->create_table('tracker_user_history');

		/*** Unique/Foreign Keys ***/
		//For whatever reason, dbforge lacks a unique/foreign key function.
		$this->db->query('
			ALTER TABLE `tracker_user_history`
				ADD CONSTRAINT `FK_tracker_user_history_tracker_chapters` FOREIGN KEY (`chapter_id`) REFERENCES `tracker_chapters` (`id`) ON UPDATE NO ACTION ON DELETE NO ACTION;'
		);
	}

	public function down() {
		$this->dbforge->drop_table('tracker_user_history', TRUE);
	}
}
