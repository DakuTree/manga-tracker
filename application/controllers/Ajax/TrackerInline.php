<?php defined('BASEPATH') or exit('No direct script access allowed');

class TrackerInline extends Auth_Controller {
	private $userID;

	public function __construct() {
		parent::__construct();

		$this->load->library('vendor/Limiter');
		$this->load->library('form_validation');

		//1000 requests per hour to either AJAX request.
		if($this->limiter->limit('tracker_general', 1000)) {
			$this->output->set_status_header('429', 'Rate limit reached'); //rate limited reached
			exit();
		}

		$this->userID = (int) $this->User->id;
	}

	/**
	 * Used locally to update the users' latest read chapter of a series.
	 *
	 * REQ_PARAMS: id, chapter
	 * METHOD:     POST
	 * URL:        /ajax/update_tracker_inline
	 */
	public function update() {
		$this->form_validation->set_rules('id',      'Chapter ID', 'required|ctype_digit');
		$this->form_validation->set_rules('chapter', 'Chapter',    'required');

		if($this->form_validation->run() === TRUE) {
			$success = $this->Tracker->updateTrackerByID($this->userID, $this->input->post('id'), $this->input->post('chapter'));
			if($success) {
				$this->output->set_status_header('200'); //Success!
			} else {
				$this->output->set_status_header('400', 'Unable to update?');
			}
		} else {
			$this->output->set_status_header('400', 'Missing/invalid parameters.');
		}
	}

	/**
	 * Used locally to remove (multiple) series from a users' list.
	 *
	 * REQ_PARAMS: id[]
	 * METHOD:     POST
	 * URL:        /ajax/delete_inline
	 */
	public function delete() {
		$this->form_validation->set_rules('id[]', 'List of IDs', 'required|ctype_digit');

		if($this->form_validation->run() === TRUE) {
			$status = $this->Tracker->deleteTrackerByIDList($this->input->post('id[]'));
			switch($status['code']) {
				case 0:
					$this->output->set_status_header('200'); //Success!
					break;
				case 1:
					$this->output->set_status_header('400', 'JSON contains invalid IDs');
					break;
				case 2:
					$this->output->set_status_header('400', 'JSON contains invalid elements.');
					break;
			}
		} else {
			$this->output->set_status_header('400', 'Request contained invalid elements!');
		}
	}

	/***** IMPORT/EXPORT ******/

	public function import() {
		$this->form_validation->set_rules('json', 'JSON String', 'required|is_valid_json');

		if($this->form_validation->run() === TRUE) {
			$status = $this->Tracker->import_tracker_from_json($this->input->post('json'));
			switch($status['code']) {
				case 0:
					//All is good!
					$this->output->set_status_header('200');
					break;
				case 1:
					$this->output->set_status_header('400', 'JSON contains invalid keys');
					break;
				case 2:
					$this->output->set_status_header('400', 'Unable to add some rows from JSON');
					$this->_render_json(json_encode($status['failed_rows']));
					break;
			}
		} else {
			if(!$this->form_validation->isRuleValid('is_valid_json')) {
				$this->output->set_status_header('400', 'File isn\'t valid JSON!');
			} else {
				$this->output->set_status_header('400', 'No file sent');
			}
		}
	}

	public function export() {
		$trackerData = $this->Tracker->export_tracker_from_user_id($this->userID);
		$this->_render_json($trackerData, TRUE);
	}

	/***** NOTES *****/
	public function tag_update() {
		$this->form_validation->set_rules('id',         'Chapter ID', 'required|ctype_digit');
		$this->form_validation->set_rules('tag_string', 'Tag String', 'max_length[255]');

		if($this->form_validation->run() === TRUE) {
			$success = $this->Tracker->updateTagsByID($this->userID, $this->input->post('id'), $this->input->post('tag_string'));

			$this->output->set_content_type('text/plain', 'UTF-8');
			$this->output->set_output("1");
		} else {
			$this->output->set_status_header('400', 'Missing/invalid parameters.');
		}
	}

	/***** CATEGORIES *****/
	public function set_category() {
		$this->form_validation->set_rules('json', 'JSON String', 'required|is_valid_json');

		if($this->form_validation->run() === TRUE) {
			$status = $this->Tracker->set_category_from_json($this->input->post('json'));
			switch($status['code']) {
				case 0:
					//All is good!
					$this->output->set_status_header('200');
					break;
				case 1:
					$this->output->set_status_header('400', 'JSON contains invalid IDs');
					break;
				case 2:
					$this->output->set_status_header('400', 'JSON contains invalid category');
					break;
			}
		} else {
			if(!$this->form_validation->isRuleValid('is_valid_json')) {
				$this->output->set_status_header('400', 'Not valid JSON!');
			} else {
				$this->output->set_status_header('400', 'No JSON sent');
			}
		}
	}
}
