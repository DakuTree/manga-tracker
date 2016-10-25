<?php

$directory = new RecursiveDirectoryIterator(getcwd()."/application/config");
$flattened = new RecursiveIteratorIterator($directory);

$files = new RegexIterator($flattened, '/^(.*\/)?(database|database_password|config|email|recaptcha|sites)\.php/');
foreach($files as $file) {
	chmod($file, 0644);
}

//Make sure .gitkeep file is recreated
touch(getcwd()."/application/tests/_ci_phpunit_test/.gitkeep");
