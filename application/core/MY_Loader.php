<?php declare(strict_types=1); defined('BASEPATH') or exit('No direct script access allowed');

class MY_Loader extends CI_Loader {
	public function __construct() {
		parent::__construct();
	}

	/**
	 * Database Loader extension, to load our version of the caching engine
	 *
	 * @access   public
	 *
	 * @param    string  $params         the DB credentials
	 * @param    bool    $return         whether to return the DB object
	 * @param    bool    $active_record  whether to enable active record (this allows us to override the config setting)
	 *
	 * @return   object
	 */
	public function database($params = '', $return = FALSE, $active_record = NULL) {
		// load our version of the CI_DB_Cache class. The database library checks
		// if this class is already loaded before instantiating it. Loading it now
		// makes sure our version is used when a controller enables query caching
		if(!class_exists('CI_DB_Cache')) {
			@include(APPPATH . 'libraries/MY_DB_cache.php');
		}

		// call the parent method to retain the CI functionality
		return parent::database($params, $return, $active_record);
	}
}
