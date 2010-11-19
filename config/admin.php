<?php defined('SYSPATH') OR die('No direct script access.');

return array(
	// A2 instance name
	'a2' => 'auth',

	'extensions' => array(
		// List of extensions to exclude from the Admin App
		'blacklist' => array(),
	),

	'widgets' => array(
		// List of widgets to exclude from the Admin App
		'blacklist' => array(),
	),

	'messages' => array(
		'session' => array(
			'type' => 'native',
			'key'  => 'admin_flash_msg',
		),
	),

	// Admin template folder path
	'template' => 'admin/themes/default',

	// Admin menu links
	'menu' => array(
		/*
		'Home'   => 'admin',
		'Users'  => 'admin/users',
		'Logout' => 'admin/logout',
		 */
	),

);

