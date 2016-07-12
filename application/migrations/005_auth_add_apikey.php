<?php defined('BASEPATH') OR exit('No direct script access allowed');

class Migration_Auth_add_apikey extends CI_Migration {
	public function __construct() {
		parent::__construct();
		$this->load->dbforge();
	}

	public function up() {
		$fields = array(
			'api_key' => array(
				'type'       => 'CHAR',
				'constraint' => '32',
				'null'       => TRUE
			)
		);
		$this->dbforge->add_column('auth_users', $fields, 'password');
	}

	public function down() {
		$this->dbforge->drop_column('auth_users', 'api_key');
	}
}
