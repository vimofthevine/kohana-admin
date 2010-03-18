<?php defined('SYSPATH') OR die('No direct script access.');

return array(
	// Admin template folder path
	'template' => 'admin/themes/tpd',

	// Admin menu hierarchy array
	'menu' => array(
		'Home'   => array('admin/main'),
		'Users'  => array('admin/users'),
		'Logout' => array('admin/logout'),
	),

);

