<?php defined('BASEPATH') or exit('No direct script access allowed');

class Favourites extends Auth_Controller {
	public function __construct() {
		parent::__construct();
	}

	public function index(int $page = 1) : void {
		if($page === 0) redirect('user/favourites/1');

		$this->header_data['title'] = "Favourites";
		$this->header_data['page']  = "favourites";

		$favouriteData = $this->Tracker->favourites->get($page);
		$this->body_data['favouriteData'] = $favouriteData['rows'];
		$this->body_data['currentPage'] = $page;
		$this->body_data['totalPages']  = $favouriteData['totalPages'];

		if($page > $this->body_data['totalPages'] && $page <= 1) redirect('user/favourites/1');

		$this->_render_page('User/Favourites');
	}

	public function export(string $type) : void {
		$favouriteData = $this->Tracker->favourites->getAll();

		switch($type) {
			case 'json':
				$this->_render_json($favouriteData, TRUE, 'tracker-history');
				break;

			case 'csv':
				$this->output->set_content_type('text/csv', 'utf-8');
				$this->_render_content($this->Tracker->portation->arrayToCSVRecursive($favouriteData, 'Date/Time,Title,Manga URL,Site,Chapter,Chapter Number, Chapter URL', ',', '"', FALSE, TRUE), 'csv',TRUE, 'tracker-favourite');
				break;

			default:
				//404
				break;
		}
	}
}
