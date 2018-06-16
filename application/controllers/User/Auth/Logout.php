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

	public function index() : void {
		$this->header_data['title'] = 'Logout';
		$this->header_data['page']  = 'logout';

		if($this->ion_auth->logged_in()) {
			//This is called again due to logout not always logging out properly. - https://github.com/benedmunds/CodeIgniter-Ion-Auth/issues/1191#issuecomment-378934024
			$this->ion_auth->logout() && $this->ion_auth->logout();
		}
		$this->session->set_flashdata('notices', 'Logout Successful');

		delete_cookie('remember_time');

		redirect('/', 'refresh'); //TODO: Should we have a custom logout page?
	} //@codeCoverageIgnore
}
