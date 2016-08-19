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
$route['user/options']               = 'User/Options';

$route['ajax/username_check']['post'] = 'Ajax/UsernameCheck'; //rate limited
$route['ajax/get_apikey']['post']     = 'Ajax/GetKey';
$route['ajax/get_tracker']            = 'Ajax/Tracker/get';

$route['ajax/update_inline']['post']  = 'Ajax/TrackerInline/update';
$route['ajax/delete_inline']['post']  = 'Ajax/TrackerInline/delete';
$route['ajax/tag_update']['post']     = 'Ajax/TrackerInline/tag_update';
$route['ajax/set_category']['post']   = 'Ajax/TrackerInline/set_category';

$route['export_list']                 = 'Ajax/TrackerInline/export';
$route['import_list']['post']         = 'Ajax/TrackerInline/import';

$route['ajax/userscript/update']      = 'Ajax/Userscript/update';

//$route['ajax/([a-zA-Z0-9_-]+)']          = 'Ajax/$1'; //TODO: Remove me. Don't match everything.

$route['about']         = 'About';
//$route['about/terms']   = 'About/terms'; //FIXME: This isn't setup??

/*** SPECIAL ROUTING ***/
if(is_cli()) {
	$route['admin/migrate']       = 'AdminCLI/migrate';
	$route['admin/update_titles'] = 'AdminCLI/updateTitles';
	$route['admin/test']          = 'AdminCLI/test';
}

/*** DISALLOWED ROUTING ***/
// disallow everything else
$route['(.*)']                 = 'error'; //for whatever stupid reason, (:any) doesn't work here

$route['404_override'] = '';
$route['translate_uri_dashes'] = FALSE;
