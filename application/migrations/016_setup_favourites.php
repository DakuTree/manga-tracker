<?php defined('BASEPATH') OR exit('No direct script access allowed');

class Migration_Setup_Favourites extends CI_Migration {
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

			'chapter' => array(
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
		$this->dbforge->add_key('chapter_id');
		$this->dbforge->add_key('updated_at');
		$this->dbforge->create_table('tracker_favourites');

		/*** Unique/Foreign Keys ***/
		//For whatever reason, dbforge lacks a unique/foreign key function.
		$this->db->query('ALTER TABLE `tracker_favourites` ADD UNIQUE(`chapter_id`, `chapter`);');

		$this->db->query('
			ALTER TABLE `tracker_favourites`
				ADD CONSTRAINT `FK_tracker_favourites_tracker_chapters` FOREIGN KEY (`chapter_id`) REFERENCES `tracker_chapters` (`id`) ON UPDATE NO ACTION ON DELETE NO ACTION;'
		);
	}

	public function down() {
		$this->dbforge->drop_table('tracker_favourites', TRUE);
	}
}
