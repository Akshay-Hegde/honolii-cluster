<?php  defined('BASEPATH') OR exit('No direct script access allowed');

$route['sites/login'] 			= 'users/login';
$route['sites/logout'] 			= 'users/logout';
$route['sites/settings']		= 'sites_settings';
$route['sites/settings(:any)']	= 'sites_settings$1';