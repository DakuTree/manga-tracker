<?php defined('BASEPATH') OR exit('No direct script access allowed');

class MY_Security extends CI_Security {
	public function __construct() {
		parent::__construct();
	}

	//FIXME: This is pretty much just a quick hack. Not too sure if this causes any security issues.
	public function csrf_show_error() {
		header("Location: {$_SERVER['REQUEST_URI']}");
	}
}
