<?php defined('BASEPATH') or exit('No direct script access allowed');

class UsernameCheck extends AJAX_Controller {
	public function __construct() {
		parent::__construct();

		$this->load->library('Limiter');
		$this->load->library('form_validation');
	}

	/**
	 * Used by the signup to do a AJAX username check.
	 *
	 * REQ_PARAMS: username
	 * METHOD:     POST
	 * URL:        /ajax/username_check
	 */
	public function index() : void {
		$this->form_validation->set_rules('username', 'Username', 'required|max_length[100]');

		if($this->form_validation->run() === TRUE) {
			if(!$this->limiter->limit('username_check', 25)) {
				$is_unique_username = $this->form_validation->is_unique_username($this->input->post('username'));

				$data = [
					'success'    => $is_unique_username,
					'csrf_token' => $this->security->get_csrf_hash() //CHECK: Does returning this within the same query make the CSRF pointless?
				];
				$this->_render_json($data);
			} else {
				$this->output->set_status_header('429', 'Rate limit reached.'); //rate limited reached
			}
		} else {
			$this->output->set_status_header('400', 'Missing/invalid parameters.');
		}
	}
}
