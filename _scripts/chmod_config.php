<?php

$directory = new RecursiveDirectoryIterator(getcwd()."/application/config");
$flattened = new RecursiveIteratorIterator($directory);

$files = new RegexIterator($flattened, '/^(.*\/)?(database|config|email|recaptcha)\.php/');
foreach($files as $file) {
	chmod($file, 0644);
}
