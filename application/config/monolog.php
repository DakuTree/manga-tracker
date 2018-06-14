<?php defined('BASEPATH') OR exit('No direct script access allowed');

/*
 * CodeIgniter Monolog Plus
 *
 * by Josh Highland <joshhighland@venntov.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */


/* GENERAL OPTIONS */
$config['handlers'] = array('file'); // valid handlers are ci_file | file | new_relic | hipchat | stderr | papertrail
$config['channel'] = ENVIRONMENT; // channel name which appears on each line of log
$config['threshold'] = '1'; // 'ERROR' => '1', 'DEBUG' => '2',  'INFO' => '3', 'ALL' => '4'
$config['introspection_processor'] = TRUE; // add some meta data such as controller and line number to log messages

/* CI FILE - DEFAULT CODEIGNITER LOG FILE STRUCTURE
* Log to default CI log directory (must be writable ie. chmod 757).
* Filename will be encoded to current system date, ie. YYYY-MM-DD-ci.log
*/
$config['ci_file_logfile'] = APPPATH.'logs/log.php';
$config['ci_file_multiline'] = TRUE; //add newlines to the output

/* FILE HANDLER OPTIONS
 * Log to default CI log directory (must be writable ie. chmod 757).
 * Filename will be encoded to current system date, ie. YYYY-MM-DD-ci.log
*/
$config['file_logfile'] = APPPATH.'logs/ci.log';
$config['file_multiline'] = TRUE; //add newlines to the output

/* NEW RELIC OPTIONS */
$config['new_relic_app_name'] = 'APP NAME - ' . ENVIRONMENT;

/* HIPCHAT OPTIONS */
$config['hipchat_app_token'] = ''; //HipChat API Token
$config['hipchat_app_room_id'] = ''; //The room that should be alerted of the message (Id or Name)
$config['hipchat_app_notification_name'] = 'Monolog'; //Name used in the "from" field
$config['hipchat_app_notify'] = false; //Trigger a notification in clients or not
$config['hipchat_app_loglevel'] = 'WARNING'; //The minimum logging level at which this handler will be triggered

/* PAPER TRAIL OPTIONS */
$config['papertrail_host'] = ''; //xxxx.papertrailapp.com
$config['papertrail_port'] = ''; //port number
$config['papertrail_multiline'] = TRUE; //add newlines to the output

// exclusion list for pesky messages which you may wish to temporarily suppress with strpos() match
$config['exclusion_list'] = array();
