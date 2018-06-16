<?php defined('BASEPATH') OR exit('No direct script access allowed');

include APPPATH.'config/database.php'; //CI doesn't do this by default?

//NOTE: The hostname/password is assuming docker-compose is used.
$db['default']['hostname'] = 'db';
$db['default']['username'] = 'mt_dev';
$db['default']['password'] = 'dev-password';
$db['default']['db_debug'] = TRUE;
