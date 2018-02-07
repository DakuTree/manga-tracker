<?php defined('BASEPATH') OR exit('No direct script access allowed');

/* GENERAL OPTIONS */
$config['handlers'] = array('file', 'papertrail'); // valid handlers are ci_file | file | new_relic | hipchat | stderr | papertrail
$config['threshold'] = '1'; // 'ERROR' => '1', 'DEBUG' => '2',  'INFO' => '3', 'ALL' => '4'

/* PAPER TRAIL OPTIONS */
$config['papertrail_host'] = ''; //xxxx.papertrailapp.com
$config['papertrail_port'] = ''; //port number
$config['papertrail_multiline'] = TRUE; //add newlines to the output
