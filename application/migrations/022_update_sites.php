<?php defined('BASEPATH') OR exit('No direct script access allowed');

class Migration_Update_Sites_1 extends CI_Migration {
	public function __construct() {
		parent::__construct();
		$this->load->dbforge();
	}

	public function up() {
		$sitesData = json_decode(file_get_contents(APPPATH.'migrations/data/tracker_sites.json'), TRUE)['sites'];

		foreach ($sitesData as $siteData) {
			$id = $siteData['id'];
			array_walk($siteData, function(&$arr) {
				$arr = array_intersect_key($arr, array_flip(['site', 'site_class', 'status', 'use_custom']));
			});
			$this->db->update('tracker_sites', $siteData, array('id' => $id));
		}
	}

	public function down() {}
}
