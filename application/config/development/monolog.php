<?php defined('BASEPATH') OR exit('No direct script access allowed');

/* GENERAL OPTIONS */
$config['handlers'] = array('file', 'papertrail', 'cli'); // valid handlers are ci_file | file | new_relic | hipchat | stderr | papertrail
$config['threshold'] = '2'; // 'ERROR' => '1', 'DEBUG' => '2',  'INFO' => '3', 'ALL' => '4'
