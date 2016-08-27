<?php

/*
|--------------------------------------------------------------------------
| Base Site URL
|--------------------------------------------------------------------------
*/
//NOTE: This is only used by Travis/PhantomJS testing
$config['base_url']   = 'https://test.trackr.moe';
$config['static_url'] = 'https://static.test.trackr.moe';

/*
|--------------------------------------------------------------------------
| Error Logging Threshold
|--------------------------------------------------------------------------
*/
$config['log_threshold'] = 2;

if(isset($_SERVER['CI_TESTING'])) {
	$config['base_url']   = 'http://127.0.0.1:8000';
	$config['static_url'] = 'http://127.0.0.1:8000/assets';

	//We only test using http..
	$config['cookie_secure'] = FALSE;
}
