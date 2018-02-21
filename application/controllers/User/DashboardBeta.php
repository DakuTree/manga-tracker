<?php defined('BASEPATH') or exit('No direct script access allowed');

class DashboardBeta extends Auth_Controller {
	public function __construct() {
		parent::__construct();
	}

	public function index() : void {
		$this->header_data['title'] = "Dashboard Beta";
		$this->header_data['page']  = "dashboard_beta";

		$this->load->helper('form');

		$this->header_data['title'] = "Dashboard Beta";
		$this->header_data['page']  = "dashboard_beta";

		$trackerData                     = $this->Tracker->list->get();
		$this->body_data['trackerData']  = $trackerData['series'];

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

		//Dashboard Beta
		$this->body_data['siteAliases'] = str_replace('-', '.', json_encode($this->config->item('site_aliases')));

		$this->_render_page("User/DashboardBeta");
	}
}
