<?php defined('BASEPATH') OR exit('No direct script access allowed');

class Stats extends MY_Controller {
	public function __construct() {
		parent::__construct();
	}

	public function index() {
		$this->header_data['title'] = "Site Stats";
		$this->header_data['page']  = "stats";

		$this->body_data['stats'] = $this->Tracker->stats->get();

		$this->_render_page("Stats");
	}
}
