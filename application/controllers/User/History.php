<?php defined('BASEPATH') or exit('No direct script access allowed');

class History extends Auth_Controller {
	public function __construct() {
		parent::__construct();
	}

	public function index(int $page = 1) {
		if($page === 0) redirect('user/history/1');

		$this->header_data['title'] = "History";
		$this->header_data['page']  = "history";

		$historyData = $this->History->userGetHistory($page);
		$this->body_data['historyData'] = $historyData['rows'];
		$this->body_data['currentPage'] = (int) $page;
		$this->body_data['totalPages']  = $historyData['totalPages'];

		if($page > $this->body_data['totalPages'] && $page <= 1) redirect('user/history/1');

		$this->_render_page('User/History');
	}
}
