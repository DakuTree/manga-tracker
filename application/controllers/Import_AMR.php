<?php defined('BASEPATH') OR exit('No direct script access allowed');

class Import_AMR extends User_Controller {
	public function __construct() {
		parent::__construct();
	}

	public function index() {
		$this->header_data['title'] = "AMR Importer";
		$this->header_data['page']  = "import_amr";

		$this->_render_page("Import_AMR");
	}
}
