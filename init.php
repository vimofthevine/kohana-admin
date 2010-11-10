<?php defined('SYSPATH') or die('No direct script access.');

// Home/dashboard route
Route::set('admin', 'admin')
	->defaults(array(
		'directory'  => 'admin',
		'controller' => 'dashboard',
	));

// Auth routes
Route::set('admin/auth', 'admin/<action>', array(
	'action' => 'login|logout',
	))
	->defaults(array(
		'directory'  => 'admin',
		'controller' => 'auth',
		'action'     => 'login',
	));

// Admin errors
Route::set('admin/error', 'admin/error/<action>')
	->defaults(array(
		'directory'  => 'admin',
		'controller' => 'error',
		'action'     => '404',
	));

// Internal extension route
Route::set('admin/extension', 'admin/extension/<controller>(/<action>(/<id>))')
	->defaults(array(
		'directory'  => 'admin/extension',
	));

// External extension route
Route::set('admin/external', 'admin/<extension>(/<action>(/<id>))')
	->defaults(array(
		'directory'  => 'admin',
		'controller' => 'extension',
	));
