<?php defined('BASEPATH') OR exit('No direct script access allowed');

$active_group  = 'default';
$query_builder = TRUE;

$db['default'] = array(
	'dsn'          => '',
	'hostname'     => 'localhost',
	'username'     => 'mt_test',
	'password'     => '', //This is set is database_password.php
	'database'     => 'mangatracker_testing',
	'dbdriver'     => 'mysqli',
	'dbprefix'     => '',
	'pconnect'     => FALSE,
	'db_debug'     => FALSE, //test is front-facing, avoid debug
	'cache_on'     => FALSE,
	'cachedir'     => '../application/cache',
	'char_set'     => 'utf8mb4',
	'dbcollat'     => 'utf8mb4_unicode_ci',
	'swap_pre'     => '',
	'encrypt'      => FALSE,
	'compress'     => FALSE,
	'stricton'     => FALSE,
	'failover'     => array(),
	'save_queries' => TRUE
);

require_once 'database_password.php';
