<?php defined('BASEPATH') OR exit('No direct script access allowed');

class IndexC extends User_Controller {
	public function __construct() {
		parent::__construct();
	}

	public function index() : void {
		if(!$this->User->logged_in()) {
			$this->_frontpage();
		} else {
			$this->_dashboard();
		}
	}

	private function _frontpage() : void {
		$this->header_data['title'] = "Index";
		$this->header_data['page']  = "index";

		//redirect('user/login');
		$this->_render_page('FrontPage');
	}

	private function _dashboard() : void {
		$this->header_data['title'] = "Dashboard";
		$this->header_data['page']  = "dashboard";

		$trackerData                     = $this->Tracker->list->get();
		$this->body_data['trackerData']  = $trackerData['series'];
		$this->body_data['has_inactive'] = $trackerData['has_inactive'];

		$this->body_data['category_custom_1']      = ($this->User_Options->get('category_custom_1') == 'enabled' ? TRUE : FALSE);
		$this->body_data['category_custom_1_text'] = $this->User_Options->get('category_custom_1_text');

		$this->body_data['category_custom_2']      = ($this->User_Options->get('category_custom_2') == 'enabled' ? TRUE : FALSE);
		$this->body_data['category_custom_2_text'] = $this->User_Options->get('category_custom_2_text');

		$this->body_data['category_custom_3']      = ($this->User_Options->get('category_custom_3') == 'enabled' ? TRUE : FALSE);
		$this->body_data['category_custom_3_text'] = $this->User_Options->get('category_custom_3_text');

		$this->body_data['use_live_countdown_timer'] = ($this->User_Options->get('enable_live_countdown_timer') == 'enabled' ? 'true' : 'false');
		$this->body_data['mal_sync']                 = $this->User_Options->get('mal_sync');

		$this->_render_page('User/Dashboard');
	}
}
