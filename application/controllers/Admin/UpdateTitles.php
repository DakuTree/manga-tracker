<?php defined('BASEPATH') OR exit('No direct script access allowed');

class UpdateTitles extends CI_Controller {
	public function __construct() {
		parent::__construct();

		is_cli() or exit("Execute via command line: php public/index.php admin/update_titles");
	}

	public function index() {
		$this->Tracker->updateLatestChapters();
	}
}
