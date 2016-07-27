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

			$this->body_data['trackerData'] = $this->Tracker->get_tracker_from_user_id($this->User->id);

			$this->body_data['category_custom_1']            = ($this->User_Options->get('category_custom_1') == 'enabled' ? TRUE : FALSE);
			$this->body_data['category_custom_1_text']       = $this->User_Options->get('category_custom_1_text');

			$this->body_data['category_custom_2']            = ($this->User_Options->get('category_custom_2') == 'enabled' ? TRUE : FALSE);
			$this->body_data['category_custom_2_text']       = $this->User_Options->get('category_custom_2_text');

			$this->body_data['category_custom_3']            = ($this->User_Options->get('category_custom_3') == 'enabled' ? TRUE : FALSE);
			$this->body_data['category_custom_3_text']       = $this->User_Options->get('category_custom_3_text');

			$this->_render_page('User/Dashboard');
		}
	}
}
