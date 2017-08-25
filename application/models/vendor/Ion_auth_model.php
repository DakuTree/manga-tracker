<?php declare(strict_types=1); defined('BASEPATH') OR exit('No direct script access allowed');

include_once APPPATH.'models/vendor/Base_Ion_auth_model.php';
class Ion_auth_model extends Base_Ion_auth_model {
	public function __construct() {
		// We cannot override the config prior to loading the Ion Auth config as that will overwrite the changes we've made. Instead we just load it here.
		$this->load->config('ion_auth', TRUE);
		if($remember = $this->input->cookie('remember_time')) {
			$this->set_user_expire_time($remember);
		}

		parent::__construct();
	}

	public function set_user_expire_time($remember) : int {
		$expire_time = 0;
		switch($remember) {
			case '1day':
				$expire_time = 86400;
				break;

			case '3day':
				//This is default so do nothing.
				break;

			case '1week':
				$expire_time = 604800;
				break;

			case '1month':
				$expire_time = 2419200;
				break;

			case '3month':
				$expire_time = 7257600;
				break;

			default:
				//Somehow remember_time isn't set?
				break;
		}
		if($expire_time > 0) {
			$this->config->set_item_by_index('user_expire', $expire_time, 'ion_auth');
		}
		return $expire_time;
	}
}
