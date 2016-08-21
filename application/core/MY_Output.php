<?php defined('BASEPATH') OR exit('No direct script access allowed');

class MY_Output extends CI_Output {
	protected $header_set = FALSE;

	public function set_status_header($code = 200, $text = '') {
		set_status_header($code, $text);
		$this->header_set = TRUE;
		return $this;
	}

	public function is_custom_header_set() {
		return $this->header_set;
	}
}
