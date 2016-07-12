<?php defined('BASEPATH') OR exit('No direct script access allowed');

class Migration_Install_ion_auth extends CI_Migration {
	public function __construct()
	{
		parent::__construct();
		$this->load->dbforge();
	}

	public function up()
	{
		/** AUTH_GROUPS **/
		// Table structure for table 'auth_groups'
		$this->dbforge->add_field(array(
			'id' => array(
				'type'           => 'MEDIUMINT',
				'constraint'     => '8',
				'unsigned'       => TRUE,
				'auto_increment' => TRUE
			),
			'name' => array(
				'type'       => 'VARCHAR',
				'constraint' => '20',
			),
			'description' => array(
				'type'       => 'VARCHAR',
				'constraint' => '100',
			)
		));
		$this->dbforge->add_key('id', TRUE);
		$this->dbforge->create_table('auth_groups');

		// Dumping data for table 'auth_groups'
		$data = array(
			array(
				'id'          => '1',
				'name'        => 'admin',
				'description' => 'Administrator'
			),
			array(
				'id'          => '2',
				'name'        => 'members',
				'description' => 'General User'
			)
		);
		$this->db->insert_batch('auth_groups', $data);

		/** AUTH_USERS **/
		// Table structure for table 'auth_users'
		$this->dbforge->add_field(array(
			'id' => array(
				'type'           => 'MEDIUMINT',
				'constraint'     => '8',
				'unsigned'       => TRUE,
				'auto_increment' => TRUE
			),
			'ip_address' => array(
				'type'       => 'VARCHAR',
				'constraint' => '16'
			),
			'username' => array(
				'type'       => 'VARCHAR',
				'constraint' => '100',
			),
			'password' => array(
				'type'       => 'VARCHAR',
				'constraint' => '80',
			),
			'salt' => array(
				'type'       => 'VARCHAR',
				'constraint' => '40'
			),
			'email' => array(
				'type'       => 'VARCHAR',
				'constraint' => '254' //SEE: http://stackoverflow.com/a/574698
			),
			'activation_code' => array(
				'type'       => 'VARCHAR',
				'constraint' => '40',
				'null'       => TRUE
			),
			'forgotten_password_code' => array(
				'type'       => 'VARCHAR',
				'constraint' => '40',
				'null'       => TRUE
			),
			'forgotten_password_time' => array(
				'type'       => 'INT',
				'constraint' => '11',
				'unsigned'   => TRUE,
				'null'       => TRUE
			),
			'remember_code' => array(
				'type'       => 'VARCHAR',
				'constraint' => '40',
				'null'       => TRUE
			),
			'created_on' => array(
				'type'       => 'INT',
				'constraint' => '11',
				'unsigned'   => TRUE,
			),
			'last_login' => array(
				'type'       => 'INT',
				'constraint' => '11',
				'unsigned'   => TRUE,
				'null'       => TRUE
			),
			'active' => array(
				'type'       => 'TINYINT',
				'constraint' => '1',
				'unsigned'   => TRUE,
				'null'       => TRUE
			)

		));
		$this->dbforge->add_key('id', TRUE);
		$this->dbforge->create_table('auth_users');

		// Dumping data for table 'auth_users'
		$data = array(
			'id'                      => '1',
			'ip_address'              => '127.0.0.1',
			'username'                => 'administrator',
			'password'                => '$2y$08$200Z6ZZbp3RAEXoaWcMA6uJOFicwNZaqk4oDhqTUiFXFe63MG.Daa',
			'salt'                    => '',
			'email'                   => 'admin@codeanimu.net',
			'activation_code'         => NULL,
			'forgotten_password_code' => NULL,
			'created_on'              => ''.time().'',
			'last_login'              => ''.time().'',
			'active'                  => '1'
		);
		$this->db->insert('auth_users', $data);

		/** AUTH_USERS_GROUPS */
		// Table structure for table 'auth_users_groups'
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
				'unsigned'   => TRUE
			),
			'group_id' => array(
				'type'       => 'MEDIUMINT',
				'constraint' => '8',
				'unsigned'   => TRUE
			)
		));
		$this->dbforge->add_key('id', TRUE);
		$this->dbforge->create_table('auth_users_groups');

		$this->db->query('
			ALTER TABLE auth_users_groups
			ADD CONSTRAINT FK_auth_users_groups_auth_users FOREIGN KEY (user_id) REFERENCES auth_users (id) ON UPDATE NO ACTION ON DELETE NO ACTION,
			ADD CONSTRAINT FK_auth_users_groups_auth_groups FOREIGN KEY (group_id) REFERENCES auth_groups (id) ON UPDATE NO ACTION ON DELETE NO ACTION;'
		);

		// Dumping data for table 'auth_users_groups'
		$data = array(
			array(
				'id'       => '1',
				'user_id'  => '1',
				'group_id' => '1',
			),
			array(
				'id'       => '2',
				'user_id'  => '1',
				'group_id' => '2',
			)
		);
		$this->db->insert_batch('auth_users_groups', $data);


		/** AUTH_LOGIN_ATTEMPTS **/
		// Table structure for table 'auth_login_attempts'
		$this->dbforge->add_field(array(
			'id' => array(
				'type'           => 'MEDIUMINT',
				'constraint'     => '8',
				'unsigned'       => TRUE,
				'auto_increment' => TRUE
			),
			'ip_address' => array(
				'type'       => 'VARCHAR',
				'constraint' => '16'
			),
			'login' => array(
				'type'       => 'VARCHAR',
				'constraint' => '100',
				'null'       => TRUE
			),
			'time' => array(
				'type'       => 'INT',
				'constraint' => '11',
				'unsigned'   => TRUE,
				'null'       => TRUE
			)
		));
		$this->dbforge->add_key('id', TRUE);
		$this->dbforge->create_table('auth_login_attempts');

	}

	public function down()
	{
		$this->dbforge->drop_table('auth_users', TRUE);
		$this->dbforge->drop_table('auth_groups', TRUE);
		$this->dbforge->drop_table('auth_users_groups', TRUE);
		$this->dbforge->drop_table('auth_login_attempts', TRUE);
	}
}
