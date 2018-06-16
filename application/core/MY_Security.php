<?php defined('BASEPATH') OR exit('No direct script access allowed');

class MY_Security extends CI_Security {
	public $verified = TRUE;

	public function __construct() {
		parent::__construct();
	}

	public function csrf_show_error() : void {
		$this->verified = FALSE;
		//CHECK: We handle the other half of this in MY_Form_Validation, does this cause any issues?
		//header('Location: ' . htmlspecialchars($_SERVER['REQUEST_URI']), TRUE, 200);
	}
}
