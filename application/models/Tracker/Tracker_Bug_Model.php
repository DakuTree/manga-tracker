<?php declare(strict_types=1); defined('BASEPATH') OR exit('No direct script access allowed');

class Tracker_Bug_Model extends Tracker_Base_Model {
	public function __construct() {
		parent::__construct();
	}


	public function report(string $text, $userID = NULL, $url = NULL) : bool {
		$this->load->library('email');

		//This is pretty barebones bug reporting, and honestly not a great way to do it, but it works for now (until the Github is public).
		$body = "".
		        (!is_null($url) && !empty($url) ? "URL: ".htmlspecialchars(substr($url, 0, 255))."<br>\n" : "").
		        "Submitted by: ".$this->input->ip_address().(!is_null($userID) ? "| {$userID}" : "")."<br>\n".
		        "<br>Bug report: ".htmlspecialchars(substr($text, 0, 1000));

		$success = TRUE;
		$this->email->from('bug-report@trackr.moe', $this->config->item('site_title', 'ion_auth'));
		$this->email->to($this->config->item('admin_email', 'ion_auth'));
		$this->email->subject($this->config->item('site_title', 'ion_auth')." - Bug Report");
		$this->email->message($body);
		if(!$this->email->send()) {
			$success = FALSE;
		}
		return $success;
	}

}
