<?php declare(strict_types=1); defined('BASEPATH') or exit('No direct script access allowed');

/**
 * Checks if view file exists.
 * @param string $path Location of view file. Use without /views/ & .php
 * @return bool
 */
function view_exists(string $path) : bool {
	return (is_string($path) && file_exists(APPPATH . "/views/{$path}.php"));
}

function get_time_class(string $time_string) : string {
	$time = strtotime($time_string);

	if(is_int($time)) {
		if($time < strtotime('-1 month')) {
			//More than a month old.
			$time_string = "sprite-month";
		} elseif($time < strtotime('-1 week')) {
			//More than a week old, but less than a month old.
			$time_string = "sprite-week";
		} else {
			//Less than a week old.
			$time_string = "sprite-day";
		}
	} else {
		$time_string = "sprite-error"; //TODO: Create the sprite for this
	}
	return $time_string;
}
