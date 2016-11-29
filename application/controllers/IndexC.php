<?php defined('BASEPATH') OR exit('No direct script access allowed');

class IndexC extends User_Controller {
	public function __construct() {
		parent::__construct();
	}

	public function index() {
		if(!$this->User->logged_in()) {
			$this->header_data['title'] = "Index";
			$this->header_data['page']  = "index";

			//FIXME: I'm not a designer, and I have no idea how to go about making a front page for the site.
			//       We're just going to redirect the user until we can get one done.

			//redirect('user/login');
			$this->_render_page('FrontPage');
		} else {
			$this->header_data['title'] = "Dashboard";
			$this->header_data['page']  = "dashboard";

			$trackerData                     = $this->Tracker->get_tracker_from_user_id($this->User->id);
			$this->body_data['trackerData']  = $trackerData['series'];
			$this->body_data['has_inactive'] = $trackerData['has_inactive'];

			$this->body_data['category_custom_1']      = ($this->User_Options->get('category_custom_1') == 'enabled' ? TRUE : FALSE);
			$this->body_data['category_custom_1_text'] = $this->User_Options->get('category_custom_1_text');

			$this->body_data['category_custom_2']      = ($this->User_Options->get('category_custom_2') == 'enabled' ? TRUE : FALSE);
			$this->body_data['category_custom_2_text'] = $this->User_Options->get('category_custom_2_text');

			$this->body_data['category_custom_3']      = ($this->User_Options->get('category_custom_3') == 'enabled' ? TRUE : FALSE);
			$this->body_data['category_custom_3_text'] = $this->User_Options->get('category_custom_3_text');

			$this->body_data['use_live_countdown_timer'] = ($this->User_Options->get('enable_live_countdown_timer') == 'enabled' ? 'true' : 'false');

			$this->_render_page('User/Dashboard');
		}
	}
}
