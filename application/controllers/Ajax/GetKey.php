<?php defined('BASEPATH') or exit('No direct script access allowed');

class GetKey extends AJAX_Controller {
	public function __construct() {
		parent::__construct();

		$this->load->library('vendor/Limiter');
		$this->load->library('form_validation');
	}

	/**
	 * Used to generate the API Key the userscript can use.
	 *
	 * REQ_PARAMS: N/A
	 * METHOD:     POST
	 * URL:        /ajax/get_apikey
	 */
	public function index() : void {
		if($this->ion_auth->logged_in()) {
			if(!$this->limiter->limit('new_api_key', 10)) {
				$api_key = $this->User->get_new_api_key();
				$json    = ['api-key' => $api_key];

				$this->output
					->set_content_type('application/json')
					->set_output(json_encode($json));
			} else {
				$this->output->set_status_header('429', 'Rate limit reached.');
			}
		} else {
			$this->output->set_status_header('400', 'Not logged in.');
		}
	}

}
