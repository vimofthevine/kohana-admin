<?php defined('SYSPATH') or die('No direct script access.');

return array(
	// Authentication messages
	'login' => array(
		'already'  => 'You are already logged in.',
		'required' => 'You must be logged in to do that.',
		'success'  => 'Welcome back, :name!',
	),
	'logout' => array(
		'success'  => 'You have been logged out.  Goodbye!',
	),
	// Resource=>privilege denial messages
	'user' => array(
		'view'     => 'You do not have permission to view :resource\'s details.',
		'create'   => 'You do not have permission to create new users.',
		'edit'     => 'You do not have permission to modify :resource.',
		'delete'   => 'You do not have permission to delete :resource.',
		'manage'   => 'You do not have permission to manage users.',
	),
);
