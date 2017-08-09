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
		$this->load->helper('form');

		$this->header_data['title'] = "Dashboard";
		$this->header_data['page']  = "dashboard";

		$trackerData                     = $this->Tracker->list->get();
		$this->body_data['trackerData']  = $trackerData['series'];
		$this->body_data['has_inactive'] = $trackerData['has_inactive'];
		$this->body_data['inactive_titles'] = $trackerData['inactive_titles'];

		$this->body_data['category_custom_1']      = ($this->User_Options->get('category_custom_1') == 'enabled' ? TRUE : FALSE);
		$this->body_data['category_custom_1_text'] = $this->User_Options->get('category_custom_1_text');

		$this->body_data['category_custom_2']      = ($this->User_Options->get('category_custom_2') == 'enabled' ? TRUE : FALSE);
		$this->body_data['category_custom_2_text'] = $this->User_Options->get('category_custom_2_text');

		$this->body_data['category_custom_3']      = ($this->User_Options->get('category_custom_3') == 'enabled' ? TRUE : FALSE);
		$this->body_data['category_custom_3_text'] = $this->User_Options->get('category_custom_3_text');

		$this->body_data['use_live_countdown_timer'] = ($this->User_Options->get('enable_live_countdown_timer') == 'enabled' ? 'true' : 'false');
		$this->body_data['mal_sync']                 = $this->User_Options->get('mal_sync');

		$this->body_data['list_sort_type'] = array_intersect_key(
			array(
				'unread'        => 'Unread (Alphabetical)',
				'unread_latest' => 'Unread (Latest Release)',
				'alphabetical'  => 'Alphabetical',
				'my_status'     => 'My Status',
				'latest'        => 'Latest Release'
			),
			array_flip(array_values($this->User_Options->options['list_sort_type']['valid_options']))
		);
		$this->body_data['list_sort_type_selected'] = $this->User_Options->get('list_sort_type');

		$this->body_data['list_sort_order'] = array_intersect_key(
			array(
				'asc'  => 'ASC',
				'desc' => 'DESC'
			),
			array_flip(array_values($this->User_Options->options['list_sort_order']['valid_options']))
		);
		$this->body_data['list_sort_order_selected'] = $this->User_Options->get('list_sort_order');

		$this->_render_page('User/Dashboard');
	}
}
