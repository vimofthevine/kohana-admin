<?php defined('SYSPATH') OR die('No direct script access.');

return array(
	// Admin template folder path
	'template' => 'admin/themes/default',

	// Admin menu hierarchy array
	'menu' => array(
		'home'   => array('admin/main'),
		'users'  => array('admin/users'),
		'logout' => array('admin/logout'),
	),

);

