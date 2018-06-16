<?php defined('BASEPATH') OR exit('No direct script access allowed');

$active_group  = 'default';
$query_builder = TRUE;

//NOTE: The hostname/password is assuming docker-compose is used.
$db['default']['hostname'] = 'db';
$db['default']['username'] = 'mt_dev';
$db['default']['password'] = 'dev-password';
$db['default']['db_debug'] = TRUE;
