<?php defined('BASEPATH') OR exit('No direct script access allowed');

class TitleHistory extends Auth_Controller {
	public function __construct() {
		parent::__construct();
	}

	/**
	 * @param int $titleID
	 * @param int $page
	 */
	public function index(int $titleID, int $page = 1) : void {
		$this->header_data['title'] = "Title History";
		$this->header_data['page']  = "history";

		//CHECK: Should we only allow people to see history for series they are tracking?
		$historyData = $this->History->getTitleHistory((int) $titleID, $page);

		$this->body_data['title']       = $historyData['title'];
		$this->body_data['historyData'] = $historyData['rows'];
		$this->body_data['currentPage'] = $page;
		$this->body_data['totalPages']  = $historyData['totalPages'];
		$this->body_data['titleID']     = (int) $titleID;

		if($page > $this->body_data['totalPages'] && $page > 1) redirect("/history/{$titleID}/1");

		$this->_render_page("TitleHistory");
	}
}
