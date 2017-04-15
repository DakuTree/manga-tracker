<?php declare(strict_types=1);  defined('BASEPATH') OR exit('No direct script access allowed');

class MY_Migration extends CI_Migration {
	public function __construct() {
		parent::__construct();
	}

	/**
	 * Insert batch statement
	 *
	 * Generates a platform-specific insert string from the supplied data.
	 *
	 * @param	string	$table	Table name
	 * @param	array	$keys	INSERT keys
	 * @param	array	$values	INSERT values
	 * @return	string
	 */
	protected function _insert_batch($table, $keys, $values) {
		$value_string = str_replace("'DEFAULT'", 'DEFAULT', implode(', ', $values));
		return 'INSERT INTO '.$table.' ('.implode(', ', $keys).') VALUES '.$value_string;
	}
}
