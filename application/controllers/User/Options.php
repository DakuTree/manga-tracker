<?php defined('BASEPATH') or exit('No direct script access allowed');

class Options extends Auth_Controller {
	function __construct() {
		parent::__construct();

		$this->load->helper('form');
		$this->load->library('form_validation');
	}

	public function index() {
		$this->header_data['title'] = "Options";
		$this->header_data['page']  = "options";

		$this->_render_page('User/Options');
	}
}
