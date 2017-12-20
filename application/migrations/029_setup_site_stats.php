<?php defined('BASEPATH') OR exit('No direct script access allowed');

class Migration_Setup_Site_Stats extends CI_Migration {
	public function __construct() {
		parent::__construct();
		$this->load->dbforge();
	}

	public function up() {
		//Site Stats table
		$this->dbforge->add_field(array(
			'date' => array(
				'type'           => 'DATE'
			),
			'total_requests' => array(
				'type'       => 'INT',
				'constraint' => '10',
				'unsigned'   => TRUE,
				'default'    => '0',
				'null'       => FALSE
			)
		));
		$this->dbforge->add_key('date', TRUE);
		$this->dbforge->create_table('site_stats');
	}

	public function down() {
		$this->dbforge->drop_table('site_stats', TRUE);
	}
}
