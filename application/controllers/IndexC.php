<?php defined('BASEPATH') OR exit('No direct script access allowed');

class IndexC extends User_Controller {
	public function __construct() {
		parent::__construct();
	}

	public function index() {
		if(!$this->User->logged_in()) {
			$this->header_data['title'] = "Index";
			$this->header_data['page']  = "index";
			$this->_render_page('FrontPage');
		} else {
			$this->header_data['title'] = "Dashboard";
			$this->header_data['page']  = "dashboard";

			$this->body_data['trackerData'] = $this->Tracker_Model->get_tracker_from_user_id($this->User->id);
			$this->_render_page('User/Dashboard');
		}
	}
}
