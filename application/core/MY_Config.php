<?php defined('BASEPATH') OR exit('No direct script access allowed');

class MY_Config extends CI_Config {
	public function __construct() {
		parent::__construct();
	}

	/**
	 * Set a config file item by index
	 *
	 * @param	string	$item	Config item key
	 * @param	string	$value	Config item value
	 * @param	string	$index	Config item index
	 * @return	void
	 */
	public function set_item_by_index($item, $value, $index) {
		$this->config[$index][$item] = $value;
	}
}
