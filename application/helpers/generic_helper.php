<?php declare(strict_types=1); defined('BASEPATH') or exit('No direct script access allowed');

/**
 * Checks if view file exists.
 * @param string $path Location of view file. Use without /views/ & .php
 * @return bool
 */
function view_exists(string $path) : bool {
	return file_exists(APPPATH . "/views/{$path}.php");
}

function get_time_class(string $time_string) : string {
	$time = strtotime($time_string);

	if(is_int($time)) {
		if($time < TIMEAGO_MONTH) {
			//More than a month old.
			$time_string = "sprite-month";
		} elseif($time < TIMEAGO_WEEK) {
			//More than a week old, but less than a month old.
			$time_string = "sprite-week";
		} elseif($time < TIMEAGO_3DAY) {
			//More than 3 days old but less than a week old.
			$time_string = "sprite-3day";
		} else {
			//Less than a week old.
			$time_string = "sprite-day";
		}
	} else {
		$time_string = "sprite-error";
	}
	return $time_string;
}

if (!function_exists('http_parse_headers')) { #http://www.php.net/manual/en/function.http-parse-headers.php#112917
	function http_parse_headers (string $raw_headers) : array {
		$headers = array(); // $headers = [];
		foreach (explode("\n", $raw_headers) as $i => $h) {
			$h = explode(':', $h, 2);
			if (isset($h[1])){
				if(!isset($headers[$h[0]])){
					$headers[$h[0]] = trim($h[1]);
				}else if(is_array($headers[$h[0]])){
					$tmp = array_merge($headers[$h[0]],array(trim($h[1])));
					$headers[$h[0]] = $tmp;
				}else{
					$tmp = array_merge(array($headers[$h[0]]),array(trim($h[1])));
					$headers[$h[0]] = $tmp;
				}
			}
		}
		return $headers;
	}
}
