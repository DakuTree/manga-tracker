<?php declare(strict_types=1); defined('BASEPATH') or exit('No direct script access allowed');

//Because we can't autoload the cache driver with params - https://forum.codeigniter.com/thread-62217-post-319687.html#pid319687
class Cacher {
	protected $CI;

	public function __construct() {
		$this->CI =& get_instance(); //grab an instance of CI
		$this->initiate_cache();
	}

	public function initiate_cache() {
		$this->CI->load->driver('cache', array('adapter' => 'file', 'backup' => 'apc')); //Sadly we can't autoload this with params
		//FIXME: We would just use APC caching, but it's a bit more tricky to manage with multiple site setups..
	}
}
