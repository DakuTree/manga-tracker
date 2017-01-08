<?php declare(strict_types=1); defined('BASEPATH') OR exit('No direct script access allowed');

class Tracker_Base_Model extends CI_Model {
	public $sites;
	public $enabledCategories;

	public function __construct() {
		parent::__construct();

		$this->load->database();

		$this->enabledCategories = [
			'reading'      => 'Reading',
			'on-hold'      => 'On-Hold',
			'plan-to-read' => 'Plan to Read'
		];
		if($this->User_Options->get('category_custom_1') == 'enabled') {
			$this->enabledCategories['custom1'] = $this->User_Options->get('category_custom_1_text');
		}
		if($this->User_Options->get('category_custom_2') == 'enabled') {
			$this->enabledCategories['custom2'] = $this->User_Options->get('category_custom_2_text');
		}
		if($this->User_Options->get('category_custom_3') == 'enabled') {
			$this->enabledCategories['custom3'] = $this->User_Options->get('category_custom_3_text');
		}

		require_once(APPPATH.'models/Site_Model.php');
		$this->sites = new Sites_Model;
	}
}
