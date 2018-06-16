<?php defined('BASEPATH') OR exit('No direct script access allowed');

include APPPATH.'config/database.php'; //CI doesn't do this by default?

$db['default']['username'] = 'mt_prod';

require 'database_password.php';
