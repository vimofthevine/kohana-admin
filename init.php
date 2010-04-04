<?php defined('SYSPATH') or die('No direct script access.');

Route::set('admin_auth', 'admin/<action>', array(
	'action' => 'login|logout',
	))
	->defaults(array(
		'directory'  => 'admin',
		'controller' => 'auth',
	));

Route::set('admin_media', 'admin/media(/<file>)', array('file'=>'.+'))
	->defaults(array(
		'directory'  => 'admin',
		'controller' => 'media',
		'action'     => 'file',
		'file'       => NULL,
	));

Route::set('admin_main', 'admin(/<controller>(/<action>(/<id>)))')
	->defaults(array(
		'directory'  => 'admin',
		'controller' => 'main',
		'action'     => 'index',
	));

// Static file serving (CSS, JS, images)
//Route::set('docs/media', 'guide/media(/<file>)', array('file' => '.+'))
	//->defaults(array(
		//'controller' => 'userguide',
		//'action'     => 'media',
		//'file'       => NULL,
	//));

// API Browser
//Route::set('docs/api', 'guide/api(/<class>)', array('class' => '[a-zA-Z0-9_]+'))
	//->defaults(array(
		//'controller' => 'userguide',
		//'action'     => 'api',
		//'class'      => NULL,
	//));

// Translated user guide
//Route::set('docs/guide', 'guide(/<page>)', array(
		//'page' => '.+',
	//))
	//->defaults(array(
		//'controller' => 'userguide',
		//'action'     => 'docs',
	//));

