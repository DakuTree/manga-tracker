<?php declare(strict_types=1); defined('BASEPATH') or exit('No direct script access allowed');

/**
 * Checks if view file exists.
 * @param string $path Location of view file. Use without /views/ & .php
 * @return bool
 */
function view_exists(string $path) : bool {
	return (is_string($path) && file_exists(APPPATH . "/views/{$path}.php"));
}

function get_time_icon(string $time_string) : string {
	$time = strtotime($time_string);

	if($time < strtotime('-1 month')) {
		//More than a month old.
		$time_string = "month.png";
	} elseif($time < strtotime('-1 week')) {
		//More than a week old, but less than a month old.
		$time_string = "week.png";
	} else {
		//Less than a week old.
		$time_string = "day.png";
	}
	return $time_string;
}
