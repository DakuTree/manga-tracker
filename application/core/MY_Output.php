<?php defined('BASEPATH') OR exit('No direct script access allowed');

class MY_Output extends CI_Output {
	protected $current_header = [
		'code'   => 200,
		'text'   => '',
		'custom' => FALSE
	];


	public function __construct() {
		parent::__construct();
	}

	public function set_status_header($code = 200, $text = '') {
		set_status_header((int) $code, (string) $text);
		$this->current_header = [
			'code'   => (int) $code,
			'text'   => (string) $text,
			'custom' => TRUE
		];
		return $this;
	}

	public function is_custom_header_set() {
		return $this->current_header['custom'];
	}

	//This is to fix a stupid bug
	public function reset_status_header() {
		$this->set_status_header($this->current_header['code'], $this->current_header['text']);
		return $this;
	}
}
