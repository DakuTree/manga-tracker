<?php declare(strict_types=1); defined('BASEPATH') OR exit('No direct script access allowed');

class Tracker_Base_Model extends CI_Model {
	public $sites;
	public $enabledCategories;

	//FIXME: This entire enabledCategories thing could be done better.

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

		foreach (glob(APPPATH.'models/Tracker/Sites/*.php') as $filename) {
			/** @noinspection PhpIncludeInspection */
			include_once $filename;
		}
		$this->sites = new Tracker_Sites_Model;

	}

	public function getEnabledCategories(?int $userID = NULL) : array {
		$userID = (is_null($userID) ? (int) $this->User->id : $userID);

		$enabledCategories = [];
		if(is_null($userID)) {
			$enabledCategories = $this->enabledCategories;
		} else {
			$enabledCategories = [
				'reading'      => 'Reading',
				'on-hold'      => 'On-Hold',
				'plan-to-read' => 'Plan to Read'
			];
			if($this->User_Options->get('category_custom_1', $userID) == 'enabled') {
				$enabledCategories['custom1'] = $this->User_Options->get('category_custom_1_text', $userID);
			}
			if($this->User_Options->get('category_custom_2', $userID) == 'enabled') {
				$enabledCategories['custom2'] = $this->User_Options->get('category_custom_2_text', $userID);
			}
			if($this->User_Options->get('category_custom_3', $userID) == 'enabled') {
				$enabledCategories['custom3'] = $this->User_Options->get('category_custom_3_text', $userID);
			}
		}

		return $enabledCategories;
	}
}
