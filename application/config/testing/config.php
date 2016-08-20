<?php

/*
|--------------------------------------------------------------------------
| Base Site URL
|--------------------------------------------------------------------------
*/
$config['base_url']   = 'https://test.tracker.codeanimu.net';
$config['static_url'] = 'https://static.test.tracker.codeanimu.net';

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
