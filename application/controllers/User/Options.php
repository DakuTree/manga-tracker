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

		$customCategories = ['custom1' => 'category_custom_1', 'custom2' => 'category_custom_2', 'custom3' => 'category_custom_3'];
		$usedCategories   = $this->Tracker->getUsedCategories($this->User->id);

		//NOTE: The checkbox validation is handled in run()
		$this->form_validation->set_rules('category_custom_1_text',      'Custom Category 1 Text',      'trim|regex_match[/^[a-zA-Z0-9-_\\s]{0,16}$/]');
		$this->form_validation->set_rules('category_custom_2_text',      'Custom Category 2 Text',      'trim|regex_match[/^[a-zA-Z0-9-_\\s]{0,16}$/]');
		$this->form_validation->set_rules('category_custom_3_text',      'Custom Category 3 Text',      'trim|regex_match[/^[a-zA-Z0-9-_\\s]{0,16}$/]');
		$this->form_validation->set_rules('default_series_category',     'Default Series Category',     'required|is_valid_option_value[default_series_category]');
		$this->form_validation->set_rules('enable_live_countdown_timer', 'Enable Live Countdown Timer', 'required|is_valid_option_value[enable_live_countdown_timer]');

		if ($isValid = $this->form_validation->run() === TRUE) {
			foreach($customCategories as $categoryK => $category) {
				if(!in_array($categoryK, $usedCategories)) {
					$this->User_Options->set($category, $this->input->post($category) ? 'enabled' : 'disabled');
				}

				$this->User_Options->set($category.'_text', $this->input->post($category.'_text') ?? '');
			}

			$this->User_Options->set('default_series_category', $this->input->post('default_series_category'));

			$this->User_Options->set('enable_live_countdown_timer', $this->input->post('enable_live_countdown_timer'));
		}

		/*** CUSTOM CATEGORIES ***/
		foreach($customCategories as $categoryK => $category) {
			$this->body_data[$category]               = ($this->User_Options->get($category) == 'enabled' ? TRUE : FALSE);
			$this->body_data[$category.'_text']       = $this->User_Options->get($category.'_text');
			$this->body_data[$category.'_has_series'] = (int) in_array($categoryK, $usedCategories);
		}

		/*** DEFAULT CATEGORY ***/
		$this->body_data['default_series_category'] = array_intersect_key(
			array(
				'reading'      => 'Reading',
				'on-hold'      => 'On-Hold',
				'plan-to-read' => 'Plan to Read',
				'custom1'      => 'Custom Category 1',
				'custom2'      => 'Custom Category 2',
				'custom3'      => 'Custom Category 3'
			),
			array_flip(array_values($this->User_Options->options['default_series_category']['valid_options']))
		);
		$this->body_data['default_series_category_selected'] = $this->User_Options->get('default_series_category');

		/*** ENABLE LIVE JS COUNTDOWN TIMER ***/
		$this->body_data = array_merge($this->body_data, $this->User_Options->generate_radio_array(
			'enable_live_countdown_timer',
			$this->User_Options->get('enable_live_countdown_timer')
		));

		$this->_render_page('User/Options');
	}
}
