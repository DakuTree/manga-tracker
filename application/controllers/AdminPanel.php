<?php declare(strict_types=1); defined('BASEPATH') OR exit('No direct script access allowed');

class AdminPanel extends Admin_Controller {
	public function __construct() {
		parent::__construct();

		$this->load->helper('form');
		$this->load->library('form_validation');
	}

	public function index() {
		$this->header_data['title'] = "Admin Panel";
		$this->header_data['page']  = "admin-panel";

		$this->_render_page('AdminPanel');
	}

	public function update_normal() {
		set_time_limit(0);
		$this->Tracker->admin->updateLatestChapters();
	}
	public function update_custom() {
		set_time_limit(0);
		$this->Tracker->admin->updateCustom();
	}
	public function update_titles() {
		set_time_limit(0);
		$this->Tracker->admin->updateTitles();
	}
}
