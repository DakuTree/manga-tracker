<?php defined('BASEPATH') or exit('No direct script access allowed');

class Userscript extends AJAX_Controller {
	private $userID;

	public function __construct() {
		parent::__construct();

		$this->load->library('Limiter');
		$this->load->library('form_validation');

		//500 requests per hour to either AJAX request.
		if($this->limiter->limit('tracker_userscript', 1000)) {
			$this->output->set_status_header('429', 'Rate limit reached'); //rate limited reached
		}

		//API Key is required for all AJAX requests
		//We're not using set_rules here since we can't run form_validation twice.
		if($this->form_validation->required($this->input->post('api-key')) && ctype_alnum($this->input->post('api-key'))) {
			$this->userID = $this->User->get_id_from_api_key($this->input->post('api-key'));
			if(!$this->userID) {
				$this->output->set_status_header('400', 'Invalid API Key');
			}
		} else {
			$this->output->set_status_header('400', 'Missing/invalid parameters.');
		}
	}

	/**
	 * This is the main update URL for the userscript.
	 *
	 * REQ_PARAMS: api-key, manga[site], manga[title], manga[chapter]
	 * METHOD:     POST
	 * URL:        /ajax/userscript/update
	 */
	public function update() : void {
		if($this->output->is_custom_header_set()) { $this->output->reset_status_header(); return; }

		$this->form_validation->set_rules('manga[site]', 'Manga [Site]', 'required');
		$this->form_validation->set_rules('manga[title]', 'Manga [Title]', 'required');
		$this->form_validation->set_rules('manga[chapter]', 'Manga [Chapter]', 'required');

		if($this->form_validation->run() === TRUE) {
			$manga = $this->input->post('manga');

			$titleData = $this->Tracker->list->update($this->userID, $manga['site'], $manga['title'], $manga['chapter'], TRUE, TRUE);
			if($titleData) {
				$malID = $this->Tracker->list->getMalID($this->userID, $titleData['id']);
				$json = [
					'mal_sync' => $this->User_Options->get('mal_sync', $this->userID),
					'mal_id'   => $malID['id'] ?? NULL,
					'chapter'  => $titleData['chapter']
				];

				$this->output
				     ->set_status_header('200')
				     ->set_content_type('application/json', 'utf-8')
				     ->set_output(json_encode($json));
			} else {
				//TODO: We should probably try and have more verbose errors here. Return via JSON or something.
				$this->output->set_status_header('400', 'Unable to update?');
			}
		} else {
			$this->output->set_status_header('400', 'Missing/invalid parameters.');
		}
	}

	/**
	 * Favourite a chapter via the userscript.
	 *
	 * REQ_PARAMS: api-key, manga[site], manga[title], manga[chapter]
	 * METHOD:     POST
	 * URL:        /ajax/userscript/favourite
	 */
	public function favourite() : void {
		if($this->output->is_custom_header_set()) { $this->output->reset_status_header(); return; }

		if($this->limiter->limit('tracker_userscript_favourite', 250)) {
			$this->output->set_status_header('429', 'Rate limit reached'); //rate limited reached
		} else {
			$this->form_validation->set_rules('manga[site]', 'Manga [Site]', 'required');
			$this->form_validation->set_rules('manga[title]', 'Manga [Title]', 'required');
			$this->form_validation->set_rules('manga[chapter]', 'Manga [Chapter]', 'required');

			if($this->form_validation->run() === TRUE) {
				$manga = $this->input->post('manga');

				$success = $this->Tracker->favourites->set($manga['site'], $manga['title'], $manga['chapter'], $this->userID);
				if($success['bool']) {
					$this->output->set_status_header('200', $success['status']); //Success!
				} else {
					$this->output->set_status_header('400', $success['status']);
				}
			} else {
				$this->output->set_status_header('400', 'Missing/invalid parameters.');
			}
		}
	}
}
