<?php defined('BASEPATH') or exit('No direct script access allowed');

class Options extends Auth_Controller {
	function __construct() {
		parent::__construct();

		$this->load->helper('form');
		$this->load->library('form_validation');
	}

	public function index() {
		$this->header_data['title'] = "Options";
		$this->header_data['page']  = "options";

		//NOTE: The checkbox validation is handled in run()
		$this->form_validation->set_rules('category_custom_1_text', 'Custom Category 1 Text', 'trim|regex_match[/^[a-zA-Z0-9-_\\s]{0,16}$/]');
		$this->form_validation->set_rules('category_custom_2_text', 'Custom Category 2 Text', 'trim|regex_match[/^[a-zA-Z0-9-_\\s]{0,16}$/]');
		$this->form_validation->set_rules('category_custom_3_text', 'Custom Category 3 Text', 'trim|regex_match[/^[a-zA-Z0-9-_\\s]{0,16}$/]');

		$customCategories = ['custom1' => 'category_custom_1', 'custom2' => 'category_custom_2', 'custom3' => 'category_custom_3'];
		$usedCategories   = $this->Tracker->getUsedCategories($this->User->id);
		if ($isValid = $this->form_validation->run() === TRUE) {
			foreach($customCategories as $categoryK => $category) {
				if(!in_array($categoryK, $usedCategories)) {
					$this->User_Options->set($category, $this->input->post($category) ? 'enabled' : 'disabled');
				}

				$this->User_Options->set($category.'_text', $this->input->post($category.'_text') ?? '');
			}
		}

		foreach($customCategories as $categoryK => $category) {
			$this->body_data[$category]               = ($this->User_Options->get($category) == 'enabled' ? TRUE : FALSE);
			$this->body_data[$category.'_text']       = $this->User_Options->get($category.'_text');
			$this->body_data[$category.'_has_series'] = (int) in_array($categoryK, $usedCategories);
		}

		$this->_render_page('User/Options');
	}
}
