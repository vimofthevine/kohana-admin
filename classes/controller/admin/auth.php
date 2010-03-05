<?php defined('SYSPATH') OR die('No direct script access.');

/**
 * @package     Controller
 * @author      Kyle Treubig
 * @copyright   (c) 2010 Kyle Treubig
 * @license     MIT
 */
class Controller_Admin_Auth extends Controller_Template_Admin {

	/**
	 * Register controller as an admin controller
	 */
	public function before() {
		parent::before();
	}

	/**
	 * Display login form and perform login
	 */
	public function action_login() {
		if ($this->a2->logged_in())
		{
			Message::instance()->info('You are already logged in.');
			Request::instance()->redirect( Route::get('admin')->uri() );
		}

		$post = Validate::factory($_POST)
			->filter(TRUE, 'trim')
			->rule('username', 'not_empty')
			->rule('password', 'not_empty');

		if ($post->check())
		{
			$user = Sprig::factory('user', array('username'=>$post['username']))->load();

			$remember = isset($post['remember']) ? (bool) $post['remember'] : FALSE;

			if ( ! $user->loaded())
			{
				$post->error('username', 'not_found');
			}
			elseif ($this->a1->login($post['username'], $post['password'], $remember))
			{
				Message::instance()->info('Welcome back, :name!', array(':name'=>$user->username));
				$referrer = $this->session->get('referrer');
				$referrer = empty($referrer) ? Route::get('admin')->uri() : $referrer;
				$this->session->delete('referrer');
				Request::instance()->redirect($referrer);
			}
			else
			{
				$post->error('password', 'incorrect');
			}
		}

		$view = new View('admin/auth/login');
		$form = $errors = array(
			'username' => '',
			'password' => '',
			'remember' => '',
		);

		$view->form = Arr::overwrite($form, $post->as_array());
		$view->errors = Arr::overwrite($errors, $post->errors('auth'));

		$this->template->content = $view;
	}

	/**
	 * Perform user logout
	 */
	public function action_logout() {
		$this->a1->logout();
		Message::instance()->info('You have been logged out.  Goodbye!');
		Request::instance()->redirect( Route::get('admin')->uri() );
	}

}

