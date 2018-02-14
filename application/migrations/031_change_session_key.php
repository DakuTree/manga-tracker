<?php defined('BASEPATH') OR exit('No direct script access allowed');

class Migration_Change_Session_Key extends CI_Migration {
	public function __construct() {
		parent::__construct();
		$this->load->dbforge();
	}

	public function up() {
		$this->db->trans_start();
		$this->db->query('TRUNCATE `ci_sessions`');
		$this->db->query('ALTER TABLE `ci_sessions` DROP PRIMARY KEY');
		$this->db->query('ALTER TABLE `ci_sessions` ADD PRIMARY KEY (id)');
		$this->db->query('ALTER TABLE `ci_sessions` ADD KEY `ci_sessions_timestamp` (`timestamp`)');
		$this->db->trans_complete();

	}

	public function down() {}
}
