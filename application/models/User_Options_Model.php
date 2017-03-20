<?php declare(strict_types=1); defined('BASEPATH') or exit('No direct script access allowed');

class User_Options_Model extends CI_Model {
	public $options = array(
		/** GENERAL OPTIONS **/
		'category_custom_1' => array(
			'default' => 'disabled',
			'type' => 'int',
			'valid_options' => array(
				0 => 'disabled',
				1 => 'enabled'
			)
		),
		'category_custom_2' => array(
			'default' => 'disabled',
			'type' => 'int',
			'valid_options' => array(
				0 => 'disabled',
				1 => 'enabled'
			)
		),
		'category_custom_3' => array(
			'default' => 'disabled',
			'type' => 'int',
			'valid_options' => array(
				0 => 'disabled',
				1 => 'enabled'
			)
		),
		'category_custom_1_text' => array(
			'default' => 'Custom 1',
			'type' => 'string'
		),
		'category_custom_2_text' => array(
			'default' => 'Custom 2',
			'type' => 'string'
		),
		'category_custom_3_text' => array(
			'default' => 'Custom 3',
			'type' => 'string'
		),

		'enable_live_countdown_timer' => array(
			'default' => 'enabled',
			'type' => 'int',
			'valid_options' => array(
				0 => 'disabled',
				1 => 'enabled'
			)
		),

		'default_series_category' => array(
			'default' => 'reading',
			'type' => 'int',
			'valid_options' => array(
				0 => 'reading',
				1 => 'on-hold',
				2 => 'plan-to-read',

				//FIXME: (MAJOR) This should only be enabled if the custom categories are enabled
				// Problem is we can't easily check for this since the userscript uses it's own UserID, and not $this->User->id
				3 => 'custom1',
				4 => 'custom2',
				5 => 'custom3'
			)
		),

		'list_sort_type' => array(
			'default' => 'unread',
			'type' => 'int',
			'valid_options' => array(
				0 => 'unread',
				1 => 'alphabetical',
				2 => 'my_status',
				3 => 'latest'
			)
		),

		'list_sort_order' => array(
			'default' => 'asc',
			'type' => 'int',
			'valid_options' => array(
				0 => 'asc',
				1 => 'desc'
			)
		),

		'theme' => array(
			'default' => 'light',
			'type' => 'int',
			'valid_options' => array(
				0 => 'light',
				1 => 'dark'
			)
		),

		'enable_public_list' => array(
			'default' => 'disabled',
			'type' => 'int',
			'valid_options' => array(
				0 => 'disabled',
				1 => 'enabled'
			)
		),
	);

	public function __construct() {
		parent::__construct();
	}

	/**
	 * Get user option, or default option if it does not exist.
	 *
	 * @param string   $option
	 * @param int|null $userID
	 *
	 * @return mixed Returns option value as STRING, or FALSE if option does not exist.
	 */
	public function get(string $option, ?int $userID = NULL) {
		$userID = (is_null($userID) ? (int) $this->User->id : $userID);

		return $this->get_by_userid($option, $userID);
	}

	/**
	 * @param string     $option
	 * @param string|int $value
	 *
	 * @return bool
	 */
	public function set(string $option, $value) : bool {
		//TODO: Check if user is logged in, get ID
		//Check if user is logged in & set ID if so
		if($userID = $this->User->id) {
			//Check if option is valid
			if(array_key_exists($option, $this->options)) {
				//option is valid

				$idData = array(
					'user_id' => $this->User->id,
					'name'    => $option,
				);

				$valueData = array();
				if($this->options[$option]['type'] == 'int') {
					$valueData['value_int'] = array_search($value, $this->options[$option]['valid_options']);
				} else {
					$valueData['value_str'] = (string) $value;
				}

				$success = $this->set_db($idData, $valueData);
				if($success) $this->session->unset_tempdata("option_{$option}");
			} else {
				$success = FALSE;
			}
		} else {
			$success = FALSE;
		}
		return $success;
	}

	private function get_by_userid(string $option, int $userID) {
		//Check if option is valid
		if(array_key_exists($option, $this->options)) {
			//Check if userID is > 0
			if($userID) {
				//Check if user has option set.
				if($row = $this->get_db($option, $userID)) {
					//User has option set, get proper value.
					if($userValue = $this->parse_value($option, $row['value_str'], $row['value_int'])) {
						//Value is valid. Everything is good.
						$value = $userValue;
					}
				}
			}

			//Overall fallback method.
			if(!isset($value)) $value = $this->options[$option]['default'];
		} else {
			$value = FALSE;
		}
		return $value;
	}

	private function get_db(string $option, int $userID) {
		//This function assumes we've already done some basic validation.

		//FIXME: Query duplication.
		if($this->User->id !== $userID) {
			$query = $this->db->select('value_str, value_int')
			                  ->from('user_options')
			                  ->where('user_id', $userID)
			                  ->where('name',    $option)
			                  ->limit(1);
			$data = $query->get()->row_array();
		} else {
			if(!($data = $this->session->tempdata("option_{$option}"))) {
				$query = $this->db->select('value_str, value_int')
				                  ->from('user_options')
				                  ->where('user_id', $userID)
				                  ->where('name',    $option)
				                  ->limit(1);
				$data = $query->get()->row_array();
				$this->session->set_tempdata("option_{$option}", $data, 3600);
			}
		}
		return $data;
	}

	private function set_db(array $idData, array $valueData) : bool {
		if($this->db->get_where('user_options', $idData)->num_rows() === 0) {
			$data['type'] = (isset($a['value_int']) ? 0 : (isset($a['value_str']) == 'string' ? 1 : 2));
			$success = $this->db->insert('user_options', array_merge($idData, $valueData));
		} else {
			$this->db->where($idData);
			$success = $this->db->update('user_options', $valueData);
		}

		return $success;
	}

	private function parse_value(string $option, $value_str, $value_int) {
		$type = $this->options[$option]['type'];

		switch($type) {
			case 'int':
				//TODO: How exactly should we handle INT? Just DB side?
				if(in_array($value_int, array_keys($this->options[$option]['valid_options']))) {
					$value = $this->options[$option]['valid_options'][$value_int];
				}
				break;
			case 'string':
				//TODO: We should have some basically XSS checking here?
				$value = (string) $value_str;
				break;
			default:
				//This should never happen.
				break;
		}
		if(!isset($value)) $value = FALSE; //FIXME: This won't play nice with BOOL type false?

		return $value;
	}

	//Used to quickly generate an array used with form_radio.
	public function generate_radio_array(string $option, string $selected_option) {
		if(array_key_exists($option, $this->options)) {
			$base_attributes = array(
				'name' => $option,
				'id'   => $option
			);
			//FIXME: Get a better solution than str_replace for removing special characters
			$elements = array();
			foreach (array_values($this->options[$option]['valid_options']) as $valid_option) {
				$elements[$option.'_'.str_replace(',', '_', $valid_option)] = array_merge($base_attributes, array(
					'value' => $valid_option
				));
			}
			if(isset($elements[$option.'_'.str_replace(',', '_', $selected_option)])) {
				$elements[$option.'_'.str_replace(',', '_', $selected_option)]['checked'] = TRUE;
			} else {
				//This should never occur, but fallbacks are always a good idea..
				$elements[$option.'_'.$this->options[$option]['default']]['checked'] = TRUE;
			}
			//CHECK: Should we attach this to body_data here?
			return $elements;
		} else {
			return FALSE;
		}
	}
}
