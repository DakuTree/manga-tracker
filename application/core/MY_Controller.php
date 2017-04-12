<?php defined('BASEPATH') OR exit('No direct script access allowed');

class MY_Controller extends CI_Controller {
	protected $header_data = array();
	protected $body_data   = array();
	protected $footer_data = array();
	public    $global_data = array();

	public function __construct(){
		parent::__construct();

		$this->load->driver('cache', array('adapter' => 'file', 'backup' => 'apc')); //Sadly we can't autoload this with params
		//FIXME: We would just use APC caching, but it's a bit more tricky to manage with multiple site setups..

		//FIXME: This is pretty much a phpUnit hack. Without it phpUnit fails here. We need a proper way to fake user/admin testing.
		$this->global_data['user'] = ($this->ion_auth->user() ? $this->ion_auth->user()->row() : ['username' => '']);
		$this->global_data['username'] = $this->User->username;

		//TODO: Move this to a lib or something.
		$this->global_data['analytics_tracking_id'] = $this->config->item('tracking_id');

		$css_path = "css/main.{$this->User_Options->get('theme')}";
		$this->global_data['complied_css_path'] = asset_url()."{$css_path}.".filemtime("../public/assets/{$css_path}.css").".css";

		$js_path = 'js/compiled.min';
		$this->global_data['complied_js_path']  = asset_url()."{$js_path}.".filemtime("../public/assets/{$js_path}.js").".js";
	}

	public function _render_page(/*(array) $paths*/) {
		//We could just use global, but this is the only var we need in both header+footer
		$this->footer_data['page'] = $this->header_data['page'];

		$this->header_data['show_header'] = (array_key_exists('show_header', $this->header_data) ? $this->header_data['show_header'] : TRUE);
		$this->footer_data['show_footer'] = (array_key_exists('show_footer', $this->footer_data) ? $this->footer_data['show_footer'] : TRUE);

		$this->load->view('common/header', ($this->global_data + $this->header_data));
		foreach(func_get_args() as $path) {
			view_exists($path) or show_404(); //TODO (FIXME): This seems bad performance wise in the long run. Is there any reason to have it in production?

			$this->load->view($path, ($this->global_data + $this->body_data));
		}
		//using the union operator + makes sure global_data always takes priority
		//SEE: http://stackoverflow.com/a/2140094/1168377
		$this->load->view('common/footer', ($this->global_data + $this->footer_data));
	}
	public function _render_json($json_input, bool $download = FALSE) {
		$json = is_array($json_input) ? json_encode($json_input) : $json_input;

		$this->output->set_content_type('application/json', 'utf-8');
		if($download) {
			$date = date('Ymd_Hi', time());
			$this->output->set_header('Content-Disposition: attachment; filename="tracker-'.$date.'.json"');
			$this->output->set_header('Content-Length: '.strlen($json));
		}
		$this->output->set_output($json);
	}
}

class CLI_Controller extends CI_Controller {
	public function __construct() {
		parent::__construct();

		//NOTE: This should fail, assuming routes.php does handles things properly.
		//      It's good to have "just in case" fallbacks though.
		is_cli() or exit("ERROR: This controller can only be called via command line: php index.php ...");
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

		if(!$this->ion_auth->logged_in()) {
			$this->User->login_redirect();
			redirect('user/login');
		}
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

		//todo: general security stuff
	}
}
