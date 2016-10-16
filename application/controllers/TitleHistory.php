<?php defined('BASEPATH') OR exit('No direct script access allowed');

class TitleHistory extends Auth_Controller {
	public function __construct() {
		parent::__construct();
	}

	public function index(int $titleID, int $page = 1) {
		$this->header_data['title'] = "Title History";
		$this->header_data['page']  = "title-history";

		//CHECK: Should we only allow people to see history for series they are tracking?
		$historyData = $this->History->getTitleHistory((int) $titleID, $page);
		$this->body_data['historyData'] = $historyData['rows'];
		$this->body_data['currentPage'] = $page;
		$this->body_data['totalPages']  = $historyData['totalPages'];

		$this->_render_page("TitleHistory");
	}
}
