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
					$this->output->set_status_header('400', 'Request contains invalid IDs');
					break;
				case 2:
					$this->output->set_status_header('400', 'Request contains invalid elements.');
					break;
			}
		} else {
			$this->output->set_status_header('400', 'Request contained invalid elements!');
		}
	}

	/**
	 * Used to import a tracker exported list.
	 *
	 * REQ_PARAMS: json
	 * METHOD:     POST
	 * URL:        /import_list
	 */
	public function import() {
		$this->form_validation->set_rules('json', 'JSON String', 'required|is_valid_json');

		if($this->form_validation->run() === TRUE) {
			$status = $this->Tracker->importTrackerFromJSON($this->input->post('json'));
			switch($status['code']) {
				case 0:
					$this->output->set_status_header('200'); //Success
					break;
				case 1:
					$this->output->set_status_header('400', 'JSON contains invalid keys');
					break;
				case 2:
					$this->output->set_status_header('400', 'Unable to add some rows from JSON');
					//$this->_render_json(json_encode($status['failed_rows'])); //TODO: We should list what rows these are.
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

	/**
	 * Used to export a users' list.
	 *
	 * REQ_PARAMS: N/A
	 * METHOD:     GET/POST
	 * URL:        /export_list
	 */
	public function export() {
		$trackerData = $this->Tracker->exportTrackerFromUserID($this->userID);
		$this->_render_json($trackerData, TRUE);
	}

	/**
	 * Used to set chapter user tags
	 *
	 * REQ_PARAMS: id, tag_string
	 * METHOD:     POST
	 * URL:        /tag_update
	 */
	public function tag_update() {
		$this->form_validation->set_rules('id',         'Chapter ID', 'required|ctype_digit');
		$this->form_validation->set_rules('tag_string', 'Tag String', 'max_length[255]|is_valid_tag_string|not_contains[none]');

		if($this->form_validation->run() === TRUE) {
			$success = $this->Tracker->updateTagsByID($this->userID, $this->input->post('id'), $this->input->post('tag_string'));

			if($success) {
				$this->output->set_status_header('200'); //Success!
			} else {
				$this->output->set_status_header('400', 'Unable to set tags?');
			}
		} else {
			$this->output->set_status_header('400', 'Missing/invalid parameters.');
		}
	}

	/**
	 * Used to set chapter user category
	 *
	 * REQ_PARAMS: id[], category
	 * METHOD:     POST
	 * URL:        /set_category
	 */
	public function set_category() {
		$this->form_validation->set_rules('id[]',     'List of IDs',   'required|ctype_digit');
		$this->form_validation->set_rules('category', 'Category Name', 'required|is_valid_category');

		if($this->form_validation->run() === TRUE) {
			$status = $this->Tracker->setCategoryByIDList($this->input->post('id[]'), $this->input->post('category'));
			switch($status['code']) {
				case 0:
					$this->output->set_status_header('200'); //Success!
					break;
				case 1:
					$this->output->set_status_header('400', 'Request contains invalid IDs');
					break;
				case 2:
					$this->output->set_status_header('400', 'Request contains invalid category.');
					break;
			}
		} else {
			$this->output->set_status_header('400', 'Request contained invalid elements!');
		}
	}
}
