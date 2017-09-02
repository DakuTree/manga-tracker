<?php defined('BASEPATH') OR exit('No direct script access allowed');

class Migration_Update_Ion_Auth_Constraints extends CI_Migration {
	public function __construct() {
		parent::__construct();
		$this->load->dbforge();
	}

	public function up() {
		$fields = array(
			'ip_address' => array(
				'type'       => 'VARCHAR',
				'constraint' => '45'
			)
		);
		$this->dbforge->modify_column('auth_users', $fields);
		$fields = array(
			'email'      => array(
				'type'       => 'VARCHAR',
				'constraint' => '254'
			)
		);
		$this->dbforge->modify_column('auth_users', $fields);
		$fields = array(
			'salt'       => array(
				'type'       => 'VARCHAR',
				'constraint' => '40',
				'null'       => TRUE
			)
		);
		$this->dbforge->modify_column('auth_users', $fields);

		$fields2 = array(
			'ip_address' => array(
				'type'       => 'VARCHAR',
				'constraint' => '45'
			)
		);
		$this->dbforge->modify_column('auth_login_attempts', $fields2);




		//Bump email column to 254

	}

	public function down() {
	}
}
