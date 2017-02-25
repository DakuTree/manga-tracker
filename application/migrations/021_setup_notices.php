<?php defined('BASEPATH') OR exit('No direct script access allowed');

class Migration_Setup_Notices extends CI_Migration {
	public function __construct() {
		parent::__construct();
		$this->load->dbforge();
	}

	public function up() {
		//Notice table
		$this->dbforge->add_field(array(
			'id' => array(
				'type'           => 'MEDIUMINT',
				'constraint'     => '8',
				'unsigned'       => TRUE,
				'auto_increment' => TRUE
			),
			'notice' => array(
				'type'           => 'VARCHAR',
				'constraint'     => '255'
				//FOREIGN KEY
			),
			'created_at' => array(
				//Despite not actually creating the field here (it's instead done below), we still need this here so a key can be created properly.
			//	'type'    => 'TIMESTAMP',
			//	'null'    => FALSE,
			//	'on_update' => FALSE
			)
		));
		$this->dbforge->add_field('created_at TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP'); //CI is annoying and auto-appends ON UPDATE which we don't want.
		$this->dbforge->add_key('id', TRUE);
		$this->dbforge->add_key('created_at');
		$this->dbforge->create_table('tracker_notices');

		//User notice hidden table
		$this->dbforge->add_field(array(
			'user_id' => array(
				'type'           => 'MEDIUMINT',
				'constraint'     => '8',
				'unsigned'       => TRUE,
				//FOREIGN KEY
			),
			'hidden_notice_id' => array(
				'type'           => 'MEDIUMINT',
				'constraint'     => '8',
				'unsigned'       => TRUE
				//FOREIGN KEY
			)
		));
		$this->dbforge->add_key('user_id', TRUE);
		$this->dbforge->create_table('tracker_user_notices');

		/*** Unique/Foreign Keys ***/
		//For whatever reason, dbforge lacks a unique/foreign key function.
		$this->db->query('
			ALTER TABLE `tracker_user_notices`
				ADD CONSTRAINT `FK_tracker_user_notices_auth_users` FOREIGN KEY (`user_id`) REFERENCES `auth_users`(`id`) ON UPDATE NO ACTION ON DELETE NO ACTION,
				ADD CONSTRAINT `FK_tracker_user_notices_tracker_notices` FOREIGN KEY (`hidden_notice_id`) REFERENCES `tracker_notices` (`id`) ON UPDATE NO ACTION ON DELETE NO ACTION;'
		);
	}

	public function down() {
		$this->dbforge->drop_table('tracker_notices', TRUE);
		$this->dbforge->drop_table('tracker_user_notices', TRUE);
	}
}
