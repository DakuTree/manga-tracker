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
			$time_string = 'sprite-month';
		} elseif($time < TIMEAGO_WEEK) {
			//More than a week old, but less than a month old.
			$time_string = 'sprite-week';
		} elseif($time < TIMEAGO_3DAY) {
			//More than 3 days old but less than a week old.
			$time_string = 'sprite-3day';
		} else {
			//Less than a week old.
			$time_string = 'sprite-day';
		}
	} else {
		$time_string = 'sprite-error';
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

function get_notices() : string {
	$CI = get_instance();

	$notice_str = '';
	if(function_exists('validation_errors') && validation_errors()) {
		$notice_str = validation_errors();
	} elseif($CI->ion_auth->errors()) {
		$notice_str = $CI->ion_auth->errors();
	} elseif($CI->ion_auth->messages()) {
		$notice_str = $CI->ion_auth->messages();
	} elseif($notices = $CI->session->flashdata('errors')) {
		$CI->session->unset_userdata('errors'); //Sometimes we call this flashdata without redirecting, so make sure we remove it
		if(is_string($notices)) {
			$notice_str = $CI->config->item('error_start_delimiter', 'ion_auth') . $notices . $CI->config->item('error_end_delimiter', 'ion_auth');
		} elseif(is_array($notices)) {
			foreach($notices as $notice) {
				$notice_str .= $CI->config->item('error_start_delimiter', 'ion_auth') . $notice . $CI->config->item('error_end_delimiter', 'ion_auth');
			}
		}
	} elseif($notices = $CI->session->flashdata('notices')) {
		$CI->session->unset_userdata('notices'); //Sometimes we call this flashdata without redirecting, so make sure we remove it
		if(is_string($notices)) {
			$notice_str = $CI->config->item('message_start_delimiter', 'ion_auth') . $notices . $CI->config->item('message_end_delimiter', 'ion_auth');
		} elseif(is_array($notices)) {
			foreach($notices as $notice) {
				$notice_str .= $CI->config->item('message_start_delimiter', 'ion_auth') . $notice . $CI->config->item('message_end_delimiter', 'ion_auth');
			}
		}
	}
	return $notice_str;
}
function exit_ci($status = NULL) : void {
	if(ENVIRONMENT !== 'testing') {
		exit($status);
	} else {
		throw new CIPHPUnitTestExitException('exit() called');
	}
}

function array_keys_exist(array $keys, array $array) : bool {
	return !array_diff($keys, array_keys($array));
}
