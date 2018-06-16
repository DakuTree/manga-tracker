<?php defined('BASEPATH') OR exit('No direct script access allowed');

class Migration_Install_ion_auth extends CI_Migration {
	public function __construct()
	{
		parent::__construct();
		$this->load->dbforge();
	}

	public function up() : void {
		/** AUTH_GROUPS **/
		// Table structure for table 'auth_groups'
		$this->dbforge->add_field(array(
			'id' => array(
				'type'           => 'MEDIUMINT',
				'constraint'     => '8',
				'unsigned'       => TRUE,
				'auto_increment' => TRUE,
				'null'           => FALSE
			),
			'name' => array(
				'type'       => 'VARCHAR',
				'constraint' => '20',
				'null'       => FALSE
			),
			'description' => array(
				'type'       => 'VARCHAR',
				'constraint' => '100',
				'null'       => FALSE
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
				'constraint' => '45'
			),
			'username' => array(
				'type'       => 'VARCHAR',
				'constraint' => '100'
			),
			'password' => array(
				'type'       => 'VARCHAR',
				'constraint' => '80'
			),
			'salt' => array(
				'type'       => 'VARCHAR',
				'constraint' => '40',
				'null'       => TRUE
			),
			'email' => array(
				'type'       => 'VARCHAR',
				'constraint' => '254', //SEE: http://stackoverflow.com/a/574698
				'null'       => TRUE
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

		$this->db->trans_start();
		$this->db->query('ALTER TABLE `auth_users_groups` ADD CONSTRAINT `FK_auth_users_groups_auth_groups` FOREIGN KEY (`group_id`) REFERENCES `auth_groups`(`id`) ON DELETE NO ACTION ON UPDATE CASCADE');
		$this->db->query('ALTER TABLE `auth_users_groups` ADD CONSTRAINT `FK_auth_users_groups_auth_users`  FOREIGN KEY (`user_id`)  REFERENCES `auth_users`(`id`)  ON DELETE CASCADE ON UPDATE CASCADE');
		$this->db->trans_complete();

		if(ENVIRONMENT !== 'production') {
			// Dumping data for table 'auth_users'
			// NOTE: This "password" is just password, it's changed on production though so no worries here.
			$data = array(
				'id'                      => '1',
				'ip_address'              => '127.0.0.1',
				'username'                => 'administrator',
				'password'                => '$2y$08$200Z6ZZbp3RAEXoaWcMA6uJOFicwNZaqk4oDhqTUiFXFe63MG.Daa',
				'salt'                    => '',
				'email'                   => 'admin@trackr.moe',
				'activation_code'         => NULL,
				'forgotten_password_code' => NULL,
				'created_on'              => ''.time().'',
				'last_login'              => ''.time().'',
				'active'                  => '1'
			);
			$this->db->insert('auth_users', $data);

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
		}


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
				'constraint' => '45',
				'null'       => TRUE
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

	public function down() : void {
		$this->dbforge->drop_table('auth_users', TRUE);
		$this->dbforge->drop_table('auth_groups', TRUE);
		$this->dbforge->drop_table('auth_users_groups', TRUE);
		$this->dbforge->drop_table('auth_login_attempts', TRUE);
	}
}
