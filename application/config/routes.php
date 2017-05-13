<?php defined('BASEPATH') OR exit('No direct script access allowed');

/*
| -------------------------------------------------------------------------
| URI ROUTING
| -------------------------------------------------------------------------
| This file lets you re-map URI requests to specific controller functions.
|
| Typically there is a one-to-one relationship between a URL string
| and its corresponding controller class/method. The segments in a
| URL normally follow this pattern:
|
|	example.com/class/method/id/
|
| In some instances, however, you may want to remap this relationship
| so that a different class/function is called than the one
| corresponding to the URL.
|
| Please see the user guide for complete details:
|
|	http://codeigniter.com/user_guide/general/routing.html
|
| -------------------------------------------------------------------------
| RESERVED ROUTES
| -------------------------------------------------------------------------
|
| There are three reserved routes:
|
|	$route['default_controller'] = 'welcome';
|
| This route indicates which controller class should be loaded if the
| URI contains no data. In the above example, the "welcome" class
| would be loaded.
|
|	$route['404_override'] = 'errors/page_missing';
|
| This route will tell the Router which controller/method to use if those
| provided in the URL cannot be matched to a valid route.
|
|	$route['translate_uri_dashes'] = FALSE;
|
| This is not exactly a route, but allows you to automatically route
| controller and method names that contain dashes. '-' isn't a valid
| class or method name character, so it requires translation.
| When you set this option to TRUE, it will replace ALL dashes in the
| controller and method URI segments.
|
| Examples:	my-controller/index	-> my_controller/index
|		my-controller/my-method	-> my_controller/my_method
*/
$route['default_controller'] = 'IndexC';

//CHECK: Should I limit everything to the specific methods? I.E: GET or POST
//       This would avoid POST working on pages which should be gotten via GET. Does it matter?

//usernames are limited to a-Z, 0-9, _ & - characters. 4min, 15 max length
$route['user/signup']                = 'User/Auth/Signup';
$route['user/signup/(.*)']           = 'User/Auth/Signup/index/$1';
$route['user/forgot_password']       = 'User/Auth/Forgot_Password';
$route['user/reset_password/(.*)']   = 'User/Auth/Forgot_Password/reset_password/$1';
$route['user/login']                 = 'User/Auth/Login';
$route['user/logout']                = 'User/Auth/Logout';
$route['user/history']               = 'User/History';
$route['user/history/([0-9]+)']      = 'User/History/index/$1';
$route['user/history/export/(csv|json)'] = 'User/History/export/$1';
$route['user/favourites']            = 'User/Favourites';
$route['user/favourites/([0-9]+)']   = 'User/Favourites/index/$1';
$route['user/options']               = 'User/Options';

$route['list/([a-zA-Z0-9_-]+)']              = 'User/PublicList/index/$1';
$route['list/([a-zA-Z0-9_-]+)\.([a-zA-Z]+)'] = 'User/PublicList/index/$1/$2';

$route['history/([0-9]+)']           = 'TitleHistory/index/$1';
$route['history/([0-9]+)/([0-9]+)']  = 'TitleHistory/index/$1/$2';

$route['update_status'] = 'UpdateStatus';

$route['ajax/username_check']['post'] = 'Ajax/UsernameCheck'; //rate limited
$route['ajax/get_apikey']             = 'Ajax/GetKey';
//$route['ajax/get_tracker']            = 'Ajax/Tracker/get';

$route['ajax/update_inline']['post']  = 'Ajax/TrackerInline/update';
$route['ajax/ignore_inline']['post']  = 'Ajax/TrackerInline/ignore';
$route['ajax/delete_inline']['post']  = 'Ajax/TrackerInline/delete';
$route['ajax/tag_update']['post']     = 'Ajax/TrackerInline/tag_update';
$route['ajax/set_category']['post']   = 'Ajax/TrackerInline/set_category';
$route['ajax/hide_notice']['post']    = 'Ajax/TrackerInline/hide_notice';
$route['ajax/set_mal_id']['post']     = 'Ajax/TrackerInline/set_mal_id';

$route['export_list']                 = 'Ajax/TrackerInline/export';
$route['import_list']['post']         = 'Ajax/TrackerInline/import';

$route['import_amr']                  = 'Import_AMR';

$route['ajax/userscript/update']['post']     = 'Ajax/Userscript/update';
$route['ajax/userscript/favourite']['post']  = 'Ajax/Userscript/favourite';
$route['ajax/userscript/report_bug']['post'] = 'Ajax/Userscript/report_bug';

//$route['ajax/([a-zA-Z0-9_-]+)']          = 'Ajax/$1'; //TODO: Remove me. Don't match everything.

$route['report_bug']  = 'ReportBug';
$route['stats']       = 'Stats';
$route['help']        = 'Help';

$route['about']       = 'About';
$route['about/terms'] = 'About/terms';

$route['admin_panel'] = 'AdminPanel';
$route['admin_panel/update/normal'] = 'AdminPanel/update_normal';
$route['admin_panel/update/custom'] = 'AdminPanel/update_custom';
$route['admin_panel/update/titles'] = 'AdminPanel/update_titles';
$route['admin_panel/convert_mal_tags'] = 'AdminPanel/convert_mal_tags';

/*** SPECIAL ROUTING ***/
if(is_cli()) {
	$route['admin/migrate']              = 'AdminCLI/migrate';
	$route['admin/update_series']        = 'AdminCLI/updateSeries';
	$route['admin/update_series_custom'] = 'AdminCLI/updateSeriesCustom';
	$route['admin/update_titles']        = 'AdminCLI/updateTitles';
	$route['admin/test']                 = 'AdminCLI/test';
}

/*** DISALLOWED ROUTING ***/
// disallow everything else
//$route['(.*)']                 = 'error'; //for whatever stupid reason, (:any) doesn't work here

$route['404_override'] = '';
$route['translate_uri_dashes'] = FALSE;
