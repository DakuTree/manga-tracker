<?php defined('BASEPATH') OR exit('No direct script access allowed');

class Migration_Setup_Sessions extends CI_Migration {
	public function __construct()
	{
		parent::__construct();
		$this->load->dbforge();
	}

	public function up()
	{
		// Table structure for table 'ci_sessions'
		$this->dbforge->add_field(array(
			'id' => array(
				'type'       => 'VARCHAR',
				'constraint' => '40',
				'null'       => FALSE
			),
			'ip_address' => array(
				'type'       => 'VARCHAR',
				'constraint' => '45',
				'null'       => FALSE
			),
			'timestamp' => array(
				'type'       => 'INT',
				'constraint' => '10',
				'unsigned'   => TRUE,
				'default'    => '0',
				'null'       => FALSE
			),
			'data' => array(
				'type'       => 'TEXT',
				// 'default'    => '',
				'null'       => FALSE
			)
		));
		$this->dbforge->add_key(array('id', 'ip_address'), TRUE); //sess_match_ip = TRUE
		$this->dbforge->add_key('timestamp', FALSE); //NOTE: Docs label this key as "ci_sessions_timestamp", but dbforge lacks label functionality.
		$this->dbforge->create_table('ci_sessions');
	}

	public function down()
	{
		$this->dbforge->drop_table('ci_sessions', TRUE);
	}
}
