<?php defined('BASEPATH') or exit('No direct script access allowed');

class Help extends MY_Controller {
	public function __construct() {
		parent::__construct();

		$this->load->helper('form');
		$this->load->library('form_validation');
	}

	public function index() : void {
		$this->header_data['title'] = "Help";
		$this->header_data['page']  = "help";

		$this->_render_page('Help');
	}
}
