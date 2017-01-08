<?php defined('BASEPATH') OR exit('No direct script access allowed');

class Migration_Favourites_Rework extends CI_Migration {
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
			'user_id' => array(
				'type'       => 'MEDIUMINT',
				'constraint' => '8',
				'unsigned'   => TRUE,
				'null'       => FALSE
				//FOREIGN KEY
			),
			'title_id' => array(
				'type'       => 'MEDIUMINT',
				'constraint' => '8',
				'unsigned'   => TRUE,
				'null'       => FALSE
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
		$this->dbforge->add_key('user_id');
		$this->dbforge->add_key('title_id');
		$this->dbforge->add_key('updated_at');
		$this->dbforge->create_table('tracker_favourites_new');

		//We need to add data from the old favourites table
		$query = $this->db->select('tf.id AS id, tc.user_id As user_id, tc.title_id AS title_id, tf.chapter AS chapter, tf.updated_at AS updated_at')
		                  ->from('tracker_favourites AS tf')
		                  ->join('tracker_chapters AS tc', 'tf.chapter_id = tc.id', 'left')
		                  ->order_by('tf.id ASC')
		                  ->get();
		if($data = $query->result_array()) {
			$this->db->insert_batch('tracker_favourites_new', $data);
		}

		//Rename the old DB
		$this->dbforge->rename_table('tracker_favourites', 'tracker_favourites_old');

		//Rename the new one to old name
		$this->dbforge->rename_table('tracker_favourites_new', 'tracker_favourites');

		/*** Unique/Foreign Keys ***/
		//For whatever reason, dbforge lacks a unique/foreign key function.
		$this->db->query('ALTER TABLE `tracker_favourites` ADD UNIQUE(`user_id`, `title_id`, `chapter`);');

		$this->db->query('
				ALTER TABLE `tracker_favourites`
				ADD CONSTRAINT `FK_tracker_favourites_auth_users` FOREIGN KEY (`user_id`) REFERENCES `auth_users`(`id`) ON UPDATE NO ACTION ON DELETE NO ACTION,
				ADD CONSTRAINT `FK_tracker_favourites_tracker_titles` FOREIGN KEY (`title_id`) REFERENCES `tracker_titles` (`id`) ON UPDATE NO ACTION ON DELETE NO ACTION;'
		);

		//Remove old DB
		$this->dbforge->drop_table('tracker_favourites_old');
	}

	public function down() {
		//Reverting is a nope.
	}
}
