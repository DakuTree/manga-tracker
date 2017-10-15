<?php defined('BASEPATH') or exit('No direct script access allowed');

class DashboardBeta extends Auth_Controller {
	public function __construct() {
		parent::__construct();
	}

	public function index() : void {
		$this->header_data['title'] = "Dashboard Beta";
		$this->header_data['page']  = "dashboard_beta";
		$this->_render_page('User/DashboardBeta');
	}
}
