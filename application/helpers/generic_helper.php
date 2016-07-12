<?php declare(strict_types=1); defined('BASEPATH') or exit('No direct script access allowed');

/**
 * Checks if view file exists.
 * @param string $path Location of view file. Use without /views/ & .php
 * @return bool
 */
function view_exists(string $path) : bool {
	return (is_string($path) && file_exists(APPPATH . "/views/{$path}.php"));
}
