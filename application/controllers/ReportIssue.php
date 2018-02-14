<?php defined('BASEPATH') OR exit('No direct script access allowed');

class ReportIssue extends MY_Controller {
	public function __construct() {
		parent::__construct();
		$this->load->helper('form');
		$this->load->library('form_validation');
	}

	public function index() : void {
		$this->header_data['title'] = "Report Issue";
		$this->header_data['page']  = "report-issue";

		$this->form_validation->set_rules('issue_description', 'Description', 'required|max_length[255]');
		$this->form_validation->set_rules('issue_url',         'URL',         'valid_url');

		if(!empty($this->input->post('website'))) {
			log_message('warning',"Bot attempting to spam report issue form!");
			die();
		}

		$this->body_data['issue_submitted'] = FALSE;
		if($isValid = $this->form_validation->run() === TRUE) {
			//send report
			$this->body_data['issue_submitted'] = $this->Tracker->issue->report("USERID:" . $this->User->id . " ||| " . $this->input->post('issue_description'), NULL, $this->input->post('issue_url'));
		}

		if(!$isValid) {
			$this->body_data['form_url'] = array(
				'name'        => 'issue_url',

				'class'       => 'form-control',

				'value'       => $this->form_validation->set_value('issue_url') ?: $this->input->get('url', TRUE) ?: ''
			);
		}
		$this->_render_page("ReportIssue");
	}
}
