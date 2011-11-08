<?php  if (!defined('BASEPATH')) exit('No direct script access allowed');
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
| 	www.your-site.com/class/method/id/
|
| In some instances, however, you may want to remap this relationship
| so that a different class/function is called than the one
| corresponding to the URL.
|
| Please see the user guide for complete details:
|
|	http://www.codeigniter.com/user_guide/general/routing.html
*/

// Maintain admin routes
$route['newsletters/admin/templates(:any)?'] 	= 'admin/templates$1';
$route['newsletters/admin/subscribers(:any)?'] 	= 'admin/subscribers$1';
$route['newsletters/admin(:any)?'] 				= 'admin/newsletters$1';

// Simplify the cron url
$route['newsletters/cron(:any)?'] 	= 'cron/index$1';
$route['newsletters(:any)'] 		= 'newsletters$1';