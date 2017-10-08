<?php defined('BASEPATH') or exit('No direct script access allowed');

class GetList extends Auth_Controller {
	private $userID;

	public function __construct() {
		parent::__construct(FALSE);

		//CHECK: Should we limit internal AJAX requests?
		//$this->load->library('Limiter');
		//$this->load->library('form_validation');

		////1000 requests per hour to either AJAX request.
		//if($this->limiter->limit('tracker_general', 1000)) {
		//	$this->output->set_status_header('429', 'Rate limit reached'); //rate limited reached

		//	exit_ci();
		//}

		$this->userID = (int) $this->User->id;
	}

	public function index(string $category = 'all') : void {
		$data = $this->Tracker->list->get($this->userID, $category);
		$data = $this->_sanitize($data);

		$this->_render_json($data);
	}

	private function _sanitize(array $trackerData) : array {
		return $trackerData;
	}
}
