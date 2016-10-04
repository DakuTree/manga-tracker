<?php defined('BASEPATH') or exit('No direct script access allowed');

class Favourites extends Auth_Controller {
	public function __construct() {
		parent::__construct();
	}

	public function index(int $page = 1) {
		if($page === 0) redirect('user/favourites/1');

		$this->header_data['title'] = "Favourites";
		$this->header_data['page']  = "favourites";

		$favouriteData = $this->Tracker->getFavourites($page);
		$this->body_data['favouriteData'] = $favouriteData['rows'];
		$this->body_data['currentPage'] = $page;
		$this->body_data['totalPages']  = $favouriteData['totalPages'];

		if($page > $this->body_data['totalPages']) redirect('user/favourites/1');

		$this->_render_page('User/Favourites');
	}
}
