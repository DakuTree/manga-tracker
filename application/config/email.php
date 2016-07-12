<?php defined('BASEPATH') OR exit('No direct script access allowed');

/*
| -------------------------------------------------------------------
| EMAIL SENDING SETTINGS
| -------------------------------------------------------------------
*/

$config["useragent"] = "CodeIgniter";

$config["protocol"] = "smtp";
//$config["mailpath"] = "/usr/sbin/sendmail";


//SMTP SETTINGS ARE SET IN SUB FOLDERS.
$config['smtp_host'] = "smtp.gmail.com";
$config['smtp_port'] = "465";
$config["smtp_user"] = "";
$config["smtp_pass"] = "";
$config["smtp_timeout"]   = 10;
$config["smtp_keepalive"] = FALSE;
$config["smtp_crypto"]    = "ssl";

$config["wordwrap"] = TRUE;
$config["wrapchars"] = 76;

$config["mailtype"] = "html"; //this should be used specified?
$config["charset"] = "utf-8";

$config["validate"] = TRUE;

$config["priority"] = 3;

//NOTE: These must stay as \r\n. Anything else seems to cause things to break (I have literally no idea why)
$config["crlf"] = "\r\n";
$config["newline"] = "\r\n";

$config["bcc_batch_mode"] = FALSE;
$config["bcc_batch_size"] = 200;

$config["dsn"] = TRUE;
