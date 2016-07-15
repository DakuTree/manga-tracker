<?php defined('BASEPATH') OR exit('No direct script access allowed');

$active_group = 'default';
$query_builder = TRUE;

$db['default'] = array(
	'dsn'	       => '',
	'hostname'     => 'localhost',
	'username'     => 'manga-tracker_prod',
	'password'     => '', //This is set is database_password.php
	'database'     => 'mangatracker_production',
	'dbdriver'     => 'mysqli',
	'dbprefix'     => '',
	'pconnect'     => FALSE,
	'db_debug'     => FALSE,
	'cache_on'     => FALSE,
	'cachedir'     => '',
	'char_set'     => 'utf8',
	'dbcollat'     => 'utf8_unicode_ci',
	'swap_pre'     => '',
	'encrypt'      => FALSE,
	'compress'     => FALSE,
	'stricton'     => FALSE,
	'failover'     => array(),
	'save_queries' => TRUE
);

require 'database_password.php';
