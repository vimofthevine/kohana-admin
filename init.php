<?php defined('SYSPATH') or die('No direct script access.');

Route::set('admin/auth', 'admin/<action>', array(
	'action' => 'login|logout',
	))
	->defaults(array(
		'directory'  => 'admin',
		'controller' => 'auth',
		'action'     => 'login',
	));

Route::set('admin/media', 'admin/media(/<file>)', array('file'=>'.+'))
	->defaults(array(
		'directory'  => 'admin',
		'controller' => 'media',
		'action'     => 'file',
		'file'       => NULL,
	));

Route::set('admin', 'admin(/<controller>(/<action>(/<id>)))')
	->defaults(array(
		'directory'  => 'admin',
		'controller' => 'main',
		'action'     => 'index',
	));

