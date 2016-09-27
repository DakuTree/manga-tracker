<?php defined('BASEPATH') or exit('No direct script access allowed');

class History extends Auth_Controller {
	public function __construct() {
		parent::__construct();
	}

	public function index() {
		$this->header_data['title'] = "History";
		$this->header_data['page']  = "history";

		$this->body_data['historyData'] = $this->History->userGetHistory();

		$this->_render_page('User/History');
	}
}
