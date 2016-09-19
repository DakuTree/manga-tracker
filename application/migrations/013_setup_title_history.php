<?php defined('BASEPATH') OR exit('No direct script access allowed');

class Migration_Setup_Title_History extends CI_Migration {
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

			'title_id' => array(
				'type'       => 'MEDIUMINT',
				'constraint' => '8',
				'unsigned'   => TRUE,
				'null'       => FALSE
				//FOREIGN KEY
			),

			'old_chapter' => array(
				'type'       => 'VARCHAR',
				'constraint' => '255',
				'null'       => TRUE
			),
			'new_chapter' => array(
				'type'       => 'VARCHAR',
				'constraint' => '255',
				'null'       => FALSE
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
		$this->dbforge->add_key('title_id');
		$this->dbforge->add_key('updated_at');
		$this->dbforge->create_table('tracker_titles_history');

		/*** Unique/Foreign Keys ***/
		//For whatever reason, dbforge lacks a unique/foreign key function.
		$this->db->query('
			ALTER TABLE `tracker_titles_history`
				ADD CONSTRAINT `FK_tracker_titles_history_tracker_titles` FOREIGN KEY (`title_id`) REFERENCES `tracker_titles` (`id`) ON UPDATE NO ACTION ON DELETE NO ACTION;'
		);
	}

	public function down() {
		$this->dbforge->drop_table('tracker_titles_history', TRUE);
	}
}
