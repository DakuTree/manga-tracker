<?php defined('BASEPATH') or exit('No direct script access allowed');

class TrackerInline extends Auth_Controller {
	private $userID;

	public function __construct() {
		parent::__construct(FALSE);

		$this->load->library('vendor/Limiter');
		$this->load->library('form_validation');

		//1000 requests per hour to either AJAX request.
		if($this->limiter->limit('tracker_general', 1000)) {
			$this->output->set_status_header('429', 'Rate limit reached'); //rate limited reached

			exit_ci();
		}

		$this->userID = (int) $this->User->id;
	}

	/**
	 * Used locally to update the users' latest read chapter of a series.
	 *
	 * REQ_PARAMS: id, chapter
	 * METHOD:     POST
	 * URL:        /ajax/update_inline
	 */
	public function update() : void {
		$this->form_validation->set_rules('id',      'Chapter ID', 'required|ctype_digit');
		$this->form_validation->set_rules('chapter', 'Chapter',    'required');

		if($this->form_validation->run() === TRUE) {
			$success = $this->Tracker->list->updateByID($this->userID, $this->input->post('id'), $this->input->post('chapter'));
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
	public function delete() : void {
		$this->form_validation->set_rules('id[]', 'List of IDs', 'required|ctype_digit');

		if($this->form_validation->run() === TRUE) {
			$status = $this->Tracker->list->deleteByIDList($this->input->post('id[]'));
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
	public function import() : void {
		$this->form_validation->set_rules('json', 'JSON String', 'required|is_valid_json');

		if($this->form_validation->run() === TRUE) {
			$status = $this->Tracker->portation->importFromJSON($this->input->post('json'));
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
	public function export() : void {
		$trackerData = $this->Tracker->portation->export();
		$this->_render_json($trackerData, TRUE);
	}

	/**
	 * Used to set title tags
	 *
	 * REQ_PARAMS: id, tag_string
	 * METHOD:     POST
	 * URL:        /tag_update
	 */
	public function tag_update() : void {
		$this->form_validation->set_rules('id',         'Chapter ID', 'required|ctype_digit');
		$this->form_validation->set_rules('tag_string', 'Tag String', 'max_length[255]|is_valid_tag_string|not_equals[none]');

		if($this->form_validation->run() === TRUE) {
			$tag_string = $this->_clean_tag_string($this->input->post('tag_string'));

			$success = $this->Tracker->tag->updateByID($this->userID, $this->input->post('id'), $tag_string);
			if($success) {
				$this->output->set_status_header('200'); //Success!
			} else {
				$this->output->set_status_header('400', 'Unable to set tags?');
			}
		} else {
			$errorArr = $this->form_validation->error_array();
			if(in_array('max_length', $errorArr)) {
				$this->output->set_status_header('400', 'Tag string is too long! Max length is 255 characters.');
			} else if(in_array('not_equals', $errorArr)) {
				$this->output->set_status_header('400', '"none" is a restricted tag.');
			} else if(in_array('is_valid_tag_string', $errorArr)) {
				$this->output->set_status_header('400', 'Tags can only contain: lowercase a-z, 0-9, -, :, & _. They can also only have one MAL metatag.');
			} else {
				$this->output->set_status_header('400', 'Missing/invalid parameters.');
			}
		}
	}

	/**
	 * Used to mass set title tags
	 *
	 * REQ_PARAMS: id[], tag_string
	 * METHOD:     POST
	 * URL:        /tag_update
	 */
	public function mass_tag_update() : void {
		$this->form_validation->set_rules('id[]', 'List of IDs', 'required|ctype_digit');
		$this->form_validation->set_rules('tag_string', 'Tag String', 'max_length[255]|is_valid_tag_string|not_equals[none]');

		if($this->form_validation->run() === TRUE) {
			$idList = $this->input->post('id[]');
			$tags   = $this->_clean_tag_string($this->input->post('tag_string'));

			$success = FALSE;
			foreach($idList as $id) {
				if(!($success = $this->Tracker->tag->updateByID($this->userID, $id, $tags))) {
					break; //end if one id fails
				};
			}

			if($success) {
				$this->output->set_status_header('200'); //Success!
			} else {
				$this->output->set_status_header('400', 'Unable to set tags?');
			}
		} else {
			$errorArr = $this->form_validation->error_array();
			if(in_array('max_length', $errorArr)) {
				$this->output->set_status_header('400', 'Tag string is too long! Max length is 255 characters.');
			} else if(in_array('not_equals', $errorArr)) {
				$this->output->set_status_header('400', '"none" is a restricted tag.');
			} else if(in_array('is_valid_tag_string', $errorArr)) {
				$this->output->set_status_header('400', 'Tags can only contain: lowercase a-z, 0-9, -, :, & _. They can also only have one MAL metatag.');
			} else {
				$this->output->set_status_header('400', 'Missing/invalid parameters.');
			}
		}
	}

	/**
	 * @param string $tag_string
	 *
	 * @return string
	 */
	private function _clean_tag_string(string $tag_string) : string {
		$tag_array = explode(',', $tag_string);
		$tag_array = array_unique($tag_array);
		$tag_array = array_filter($tag_array);

		return implode(',', $tag_array);
	}

	/**
	 * Used to set chapter user category
	 *
	 * REQ_PARAMS: id[], category
	 * METHOD:     POST
	 * URL:        /set_category
	 */
	public function set_category() : void {
		$this->form_validation->set_rules('id[]',     'List of IDs',   'required|ctype_digit');
		$this->form_validation->set_rules('category', 'Category Name', 'required|is_valid_category');

		if($this->form_validation->run() === TRUE) {
			$status = $this->Tracker->category->setByIDList($this->input->post('id[]'), $this->input->post('category'));
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

	/**
	 * Used to permanently hide the current notice.
	 *
	 * REQ_PARAMS: [none]
	 * METHOD:     POST
	 * URL:        /ajax/hide_notice
	 */
	public function hide_notice() : void {
		$status = $this->User->hideLatestNotice();
		if($this->User->hideLatestNotice()) {
			$this->output->set_status_header('200'); //Success!
		} else {
			$this->output->set_status_header('400', 'Something went wrong');
		}
	}


	/**
	 * Used locally to ignore the latest chapter of a series so it doesn't clog up the current unread list.
	 *
	 * REQ_PARAMS: id, chapter
	 * METHOD:     POST
	 * URL:        /ajax/ignore_inline
	 */
	public function ignore() : void {
		$this->form_validation->set_rules('id',      'Chapter ID', 'required|ctype_digit');
		$this->form_validation->set_rules('chapter', 'Chapter',    'required');

		if($this->form_validation->run() === TRUE) {
			$success = $this->Tracker->list->ignoreByID($this->userID, $this->input->post('id'), $this->input->post('chapter'));
			if($success) {
				$this->output->set_status_header('200'); //Success!
			} else {
				$this->output->set_status_header('400', 'Unable to ignore?');
			}
		} else {
			$this->output->set_status_header('400', 'Missing/invalid parameters.');
		}
	}

	/**
	 * Used to set MAL ID
	 *
	 * REQ_PARAMS: id, mal_id
	 * METHOD:     POST
	 * URL:        /set_mal_id
	 */
	public function set_mal_id() : void {
		$this->form_validation->set_rules('id',     'Chapter ID', 'required|ctype_digit');
		$this->form_validation->set_rules('mal_id', 'MAL ID',     'regex_match[/^[0-9]*$/]');

		if($this->form_validation->run() === TRUE) {
			$malID = (is_numeric($this->input->post('mal_id')) ? $this->input->post('mal_id') : NULL);
			$success = $this->Tracker->list->setMalID($this->userID, $this->input->post('id'), $malID);
			if($success) {
				$this->output->set_status_header('200'); //Success!
			} else {
				$this->output->set_status_header('400', 'Unable to set MAL ID?');
			}
		} else {
			$errorArr = $this->form_validation->error_array();
			if(in_array('regex_match', $errorArr)) {
				$this->output->set_status_header('400', 'MAL id must be numeric or null.');
			} else {
				$this->output->set_status_header('400', 'Missing/invalid parameters.');
			}
		}
	}
}
