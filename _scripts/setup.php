<?php

(PHP_SAPI !== 'cli' || isset($_SERVER['HTTP_USER_AGENT'])) && die('CLI only.');

chdir(realpath(dirname(__FILE__ . '../', 2))); //Navigate to root DIR

function setup() {
	vendor_copy();

	chmod_files();

	//Make sure .gitkeep file is recreated
	touch(getcwd() . '/application/tests/_ci_phpunit_test/.gitkeep');
}

/**********************************************************************************************************************/

function vendor_copy() {
	$json = json_decode(file_get_contents('composer.json'), TRUE)['vendor-copy'];
	array_map('copy', array_keys($json), $json);
}

function chmod_files() {
	$directory = new RecursiveDirectoryIterator(getcwd() . '/application/config');
	$flattened = new RecursiveIteratorIterator($directory);

	$files = new RegexIterator($flattened, '/^(.*\/)?(database|database_password|config|email|recaptcha|sites)\.php/');
	foreach($files as $file) {
		chmod($file, 0644);
	}
}
