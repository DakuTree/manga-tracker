<?php defined('BASEPATH') OR exit('No direct script access allowed');

class About extends MY_Controller
{
	public function __construct() {
		parent::__construct();
	}

	public function index() {
		$this->header_data['title'] = "About";
		$this->header_data['page']  = "about";
		$this->_render_page("About/About");
	}
	public function terms() {
		$this->header_data['title'] = "Terms of Service";
		$this->header_data['page']  = "terms";
		$this->_render_page("About/Terms");
	}
}
