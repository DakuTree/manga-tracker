<?php defined('BASEPATH') OR exit('No direct script access allowed');

class Logout extends User_Controller {
	//we shouldn't care if the user is logged in or not, since ion_auth will take care of things

	public function __construct() {
		parent::__construct();

		$this->load->helper('cookie');

		$this->load->library('form_validation');
		$this->form_validation->set_error_delimiters(
			$this->config->item('error_start_delimiter', 'ion_auth'),
			$this->config->item('error_end_delimiter', 'ion_auth')
		);
	}

	public function index() {
		$this->header_data['title'] = "Logout";
		$this->header_data['page']  = "logout";

		//TODO (CHECK): Is there any point to checking if the user is even logged in before doing this?
		$this->ion_auth->logout();
		delete_cookie('remember_time');

		//TODO: Notify user on successful logout.
		$this->session->set_flashdata('notices', $this->ion_auth->messages());
		redirect('/'); //TODO: Should we have a custom logout page?
	} //@codeCoverageIgnore
}
