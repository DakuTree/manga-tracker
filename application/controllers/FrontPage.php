<?php defined('BASEPATH') OR exit('No direct script access allowed');

class FrontPage extends User_Controller {
	public function __construct() {
		parent::__construct();
	}

	public function index() : void {
		$this->header_data['title'] = 'Index';
		$this->header_data['page']  = 'index';

		if($this->User->logged_in()) redirect('user/dashboard');
		$this->_render_page('FrontPage');
	}
}
