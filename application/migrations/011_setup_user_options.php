<?php defined('BASEPATH') OR exit('No direct script access allowed');

class Migration_Setup_User_Options extends CI_Migration {
	public function __construct() {
		parent::__construct();
		$this->load->dbforge();
	}

	public function up() {
		$this->dbforge->add_field(array(
			'user_id' => array(
				'type'           => 'MEDIUMINT',
				'constraint'     => '8',
				'unsigned'       => TRUE
			),
			'name' => array(
				'type'       => 'VARCHAR',
				'constraint' => '255',
				'null'       => FALSE,
			),
			'type' => array(
				'type'       => 'TINYINT',
				'constraint' => '1',
				'unsigned'   => TRUE,
				'null'       => FALSE
			),
			'value_str' => array(
				'type'       => 'VARCHAR',
				'constraint' => '255',
				'null'       => TRUE
			),
			'value_int' => array(
				'type'       => 'TINYINT',
				'constraint' => '1',
				'unsigned'   => TRUE,
				'null'       => TRUE
			)
		));
		$this->dbforge->add_key(array('user_id', 'name'), TRUE);
		$this->dbforge->create_table('user_options');

		/*** Unique/Foreign Keys ***/
		//For whatever reason, dbforge lacks a unique/foreign key function.
		$this->db->query('
			ALTER TABLE `user_options`
				ADD CONSTRAINT `FK_user_options_auth_users` FOREIGN KEY (`user_id`) REFERENCES `auth_users` (`id`) ON UPDATE NO ACTION ON DELETE CASCADE;'
		);
	}

	public function down() {
		$this->dbforge->drop_table('user_options', TRUE);
	}
}
