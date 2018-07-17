<?php defined('BASEPATH') OR exit('No direct script access allowed');

class ReportIssue extends MY_Controller {
	public function __construct() {
		parent::__construct();
		$this->load->helper('form');
		$this->load->library('form_validation');
	}

	public function index() : void {
		$this->header_data['title'] = 'Report Issue';
		$this->header_data['page']  = 'report-issue';

		$this->form_validation->set_rules('issue_description', 'Description', 'required|max_length[1000]');
		$this->form_validation->set_rules('issue_url',         'URL',         'valid_url');


		$this->body_data['issue_submitted'] = FALSE;
		if($isValid = ($this->form_validation->run() === TRUE)) {
			//send report

			if(!empty($this->input->post('website'))) {
				$this->body_data['issue_submitted'] = FALSE;
				log_message('error', 'Bot attempting to spam report issue form: "' . $this->input->post('issue_description') . '"');
			} else {
				$this->body_data['issue_submitted'] = $this->Tracker->issue->report('USERID:' . $this->User->id . ' ||| ' . $this->input->post('issue_description'), NULL, $this->input->post('issue_url'));
			}
		}

		$this->body_data['form_description'] = [
			'name' => 'issue_description',

			'value' => $this->form_validation->set_value('issue_description') ?: '',

			'class' => 'form-control',
			'rows' => '3',

			'placeholder' => 'Please describe your issue and provide as much info as possible.&#10;If you have any feature requests, please use submit an issue to our Github instead.',

			'required' => TRUE
		];

		$this->body_data['form_url'] = array(
			'name'        => 'issue_url',

			'class'       => 'form-control',

			'value'       => $this->form_validation->set_value('issue_url') ?: $this->input->get('url', TRUE) ?: ''
		);

		if(!$isValid) {
			$this->body_data['form_url']['value'] = '';
		}
		$this->_render_page('ReportIssue');
	}
}
