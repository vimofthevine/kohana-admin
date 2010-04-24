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
		Kohana::$log->add(Kohana::DEBUG, 'Executing Controller_Auth::action_login');

		if ($this->a2->logged_in())
		{
			Kohana::$log->add('ACCESS', "Attempt to login made by logged-in user");
			$message = __('You are already logged in.');

			// Return message if an ajax request
			if (Request::$is_ajax)
			{
				$this->template->content = $message;
			}
			// Else set flash message and redirect
			else
			{
				Message::instance()->error($message);
				Request::instance()->redirect( Route::get('admin_main')->uri() );
			}
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
				Kohana::$log->add('ACCESS', 'Attempt to login made with unknown username, '.$post['username']);
				$post->error('username', 'not_found');
			}
			elseif ($this->a1->login($post['username'], $post['password'], $remember))
			{
				Kohana::$log->add('ACCESS', 'Successful login made with username, '.$user->username);
				$message = __('Welcome back, :name!', array(':name'=>$user->username));

				// Get referring URI, if any
				$referrer = $this->session->get('referrer');
				$referrer = empty($referrer) ? Route::get('admin_main')->uri() : $referrer;
				$this->session->delete('referrer');

				// Return message if an ajax request
				if (Request::$is_ajax)
				{
					$this->template->content = $message;
				}
				// Else set flash message and redirect
				else
				{
					Message::instance()->info($message);
					Request::instance()->redirect($referrer);
				}
			}
			else
			{
				Kohana::$log->add('ACCESS', 'Unsuccessful login attempt made with username, '.$post['username']);
				$post->error('password', 'incorrect');
			}
		}

		$form = $errors = array(
			'username' => '',
			'password' => '',
			'remember' => '',
		);

		$hmvc = View::factory('admin/auth/hmvc/login')
			->set('form', Arr::overwrite($form, $post->as_array()))
			->set('errors', Arr::overwrite($errors, $post->errors('auth')));

		$view = View::factory('admin/auth/login')
			->set('form', $hmvc);

		// Set request response
		$this->template->content = $this->internal_request ? $hmvc : $view;
	}

	/**
	 * Perform user logout
	 */
	public function action_logout() {
		Kohana::$log->add(Kohana::DEBUG, 'Executing Controller_Auth::action_logout');
		$this->a1->logout();

		Kohana::$log->add('ACCESS', 'Successful logout made by user.');
		$message = __('You have been logged out.  Goodbye!');

		// Return message if an ajax request
		if (Request::$is_ajax)
		{
			$this->template->content = $message;
		}
		// Else set flash message and redirect
		else
		{
			Message::instance()->info($message);
			Request::instance()->redirect( Route::get('admin_main')->uri() );
		}
	}

}

