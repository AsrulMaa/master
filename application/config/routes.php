<?php
defined('BASEPATH') OR exit('No direct script access allowed');

$route['default_controller'] = 'landing';
$route['404_override'] = '';
$route['translate_uri_dashes'] = FALSE;

$route['admin/users/(:num)'] = 'admin/users/index/$1';
$route['admin'] = 'admin/dashboard';
$route['users/search/(:num)'] = 'users/search/$1';

