<?php defined('BASEPATH') or exit('No direct script access allowed');

class UsernameCheck extends AJAX_Controller {
	public function __construct() {
		parent::__construct();

		$this->load->library('vendor/Limiter');
		$this->load->library('form_validation');
	}

	public function index() {
		//TODO: Setup a bunch of easy rate limit functions.
		if($username = $this->input->post('username')) {
			if(!$this->limiter->limit('email_check', 25)) {
				$username = $this->input->post('username');
				$this->output->set_output($this->form_validation->is_unique_username($username) ? "true" : "false");
			} else {
				$this->output->set_status_header('429', 'Rate limit reached.'); //rate limited reached
			}
		} else {
			$this->output->set_status_header('400');
		}
	}

}
