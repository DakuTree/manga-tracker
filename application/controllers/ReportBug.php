<?php defined('BASEPATH') OR exit('No direct script access allowed');

class ReportBug extends MY_Controller {
	public function __construct() {
		parent::__construct();
		$this->load->helper('form');
		$this->load->library('form_validation');
	}

	public function index() {
		$this->header_data['title'] = "Report Bug";
		$this->header_data['page']  = "report-bug";

		$this->form_validation->set_rules('bug_description', 'Description', 'required|max_length[255]');
		$this->form_validation->set_rules('bug_url',         'URL',         '');

		$this->body_data['bug_submitted'] = FALSE;
		if ($isValid = $this->form_validation->run() === TRUE) {
			//send report
			$this->body_data['bug_submitted'] = $this->Tracker->reportBug("USERID:".$this->User->id." ||| ".$this->input->post('bug_description'), NULL, $this->input->post('bug_url'));
		}

		$this->_render_page("ReportBug");
	}
}
