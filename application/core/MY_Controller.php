<?php defined('BASEPATH') OR exit('No direct script access allowed');

class MY_Controller extends CI_Controller {
	protected $header_data = array();
	protected $body_data   = array();
	protected $footer_data = array();
	public    $global_data = array();

	public function __construct(){
		parent::__construct();

		//FIXME: This is pretty much a phpUnit hack. Without it phpUnit fails here. We need a proper way to fake user/admin testing.
		$this->global_data['user'] = ($this->ion_auth->user() ? $this->ion_auth->user()->row() : ['username' => '']);
		$this->global_data['username'] = $this->User->username;

		//TODO: Move this to a lib or something.
		$this->global_data['analytics_tracking_id'] = $this->config->item('tracking_id');
	}

	function _render_page(/*(array) $paths*/) {
		//using the union operator + makes sure global_data always takes priority
		//SEE: http://stackoverflow.com/a/2140094/1168377

		$this->load->view('common/header', ($this->global_data + $this->header_data));
		foreach(func_get_args() as $path) {
			view_exists($path) or show_404(); //TODO (FIXME): This seems bad performance wise in the long run. Is there any reason to have it in production?

			$this->load->view($path, ($this->global_data + $this->body_data));
		}
		$this->load->view('common/footer', ($this->global_data + $this->footer_data));
	}
	function _render_json($json_input) {
		$json = is_array($json_input) ? json_encode($json_input) : $json_input;

		$this->output->set_content_type('application/json');
		$this->output->set_output($json);
	}
}

/**** AUTH CONTROLLERS ****/
class User_Controller extends MY_Controller {
	public function __construct() {
		parent::__construct();

		$this->load->database();
	}
}

class Auth_Controller extends User_Controller {
	public function __construct() {
		parent::__construct();

		if(!$this->ion_auth->logged_in()) redirect('user/login');
	}
}

class No_Auth_Controller extends User_Controller {
	//TODO: Change this name. Doesn't feel right.
	public function __construct() {
		parent::__construct();

		if($this->ion_auth->logged_in()) redirect('/');
	}
}

class Admin_Controller extends Auth_Controller {
	public function __construct() {
		parent::__construct();

		if(!$this->ion_auth->is_admin()) {
			//user is not an admin, redirect them to front page
			//TODO (CHECK): Should we note that "you must be an admin to view this page"?

			redirect('/');
		}
	}
}

/**** AJAX CONTROLLERS ****/
class AJAX_Controller extends CI_Controller {
	public function __construct() {
		parent::__construct();

		//$this->output->set_header('Access-Control-Allow-Origin: *'); //FIXME: Limit this to specific URLs
		//todo: general security stuff
	}
}
