<?php defined('BASEPATH') OR exit('No direct script access allowed');

class Migration_Setup_Rate_Limit extends CI_Migration {
	public function __construct() {
		parent::__construct();
		$this->load->dbforge();
	}

	public function up() {
		$this->dbforge->add_field(array(
			'client' => array(
				'type'       => 'VARCHAR',
				'constraint' => '255',
				'null'       => FALSE,
				'default'    => ''
			),
			'target' => array(
				'type'       => 'VARCHAR',
				'constraint' => '255',
				'null'       => FALSE,
				'default'    => '_global'
			),
			'start' => array(
				'type'       => 'TIMESTAMP',
				'null'       => FALSE,
				//'default' => 'CURRENT_TIMESTAMP',  //This is auto-added by CI
				//'on_update' => 'CURRENT_TIMESTAMP' //This is auto-added by CI
			),
			'count' => array(
				'type'       => 'INT',
				'constraint' => '11',
				'null'       => FALSE,
				'unsigned'   => TRUE,

				'default'    => '1'
			)
		));
		$this->dbforge->add_key(array('client', 'target'), TRUE);
		$this->dbforge->create_table('rate_limit');
	}

	public function down() {
		$this->dbforge->drop_table('rate_limit', TRUE);
	}
}
