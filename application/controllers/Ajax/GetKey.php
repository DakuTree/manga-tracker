<?php defined('BASEPATH') or exit('No direct script access allowed');

class GetKey extends AJAX_Controller {
	public function __construct() {
		parent::__construct();

		$this->load->library('vendor/Limiter');
		$this->load->library('form_validation');
	}

	public function index() {
		if($this->ion_auth->logged_in()) {
			if(!$this->limiter->limit('new_api_key', 25)) {
				$api_key = $this->User->get_new_api_key();
				$json = ['api-key' => $api_key];

				$this->output
					->set_content_type('application/json')
					->set_output(json_encode($json));
			} else {
				$this->output->set_status_header('429', 'Rate limit reached.'); //rate limited reached
			}
		} else {
			$this->output->set_status_header('400');
		}
	}

}
