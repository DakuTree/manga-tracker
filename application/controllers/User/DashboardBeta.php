<?php defined('BASEPATH') or exit('No direct script access allowed');

class DashboardBeta extends Auth_Controller {
	public function __construct() {
		parent::__construct();
	}

	public function index() : void {
		$this->header_data['title'] = "Dashboard Beta";
		$this->header_data['page']  = "dashboard_beta";

		$this->body_data['siteAliases'] = str_replace('-', '.', json_encode($this->config->item('site_aliases')));

		//TEMP: We're loading manually as we need to test Bootstrap 4.
		$this->footer_data['page'] = $this->header_data['page'];

		$this->header_data['show_header'] = (array_key_exists('show_header', $this->header_data) ? $this->header_data['show_header'] : TRUE);
		$this->footer_data['show_footer'] = (array_key_exists('show_footer', $this->footer_data) ? $this->footer_data['show_footer'] : TRUE);

		$this->load->view('common/headerBeta', ($this->global_data + $this->header_data));
		$this->load->view('User/DashboardBeta', ($this->global_data + $this->body_data));
		$this->load->view('common/footerBeta', ($this->global_data + $this->footer_data));
	}
}
