<?php defined('SYSPATH') or die('No direct script access.');

/**
 * Authentication Controller
 *
 * @package     Admin
 * @category    Controller
 * @author      Kyle Treubig
 * @copyright   (c) 2010 Kyle Treubig
 * @license     MIT
 */
class Controller_Admin_Auth extends Controller_Admin {

	protected $_view_map = array(
		'login'   => 'admin/layout/narrow_column',
	);

	protected $_current_nav = 'admin/login';

	/**
	 * Display login form and perform login
	 */
	public function action_login() {
		Kohana::$log->add(Kohana::DEBUG, 'Executing Controller_Auth::action_login');

		// If user is already logged in, redirect to admin main
		if ($this->a2->logged_in())
		{
			Kohana::$log->add('ACCESS', "Attempt to login made by logged-in user");
			Kohana::$log->add(Kohana::DEBUG, "Attempt to login made by logged-in user");
			Message::instance()->error(Kohana::message('a2', 'login.already'));
			$this->request->redirect( Route::get('admin')->uri() );
		}

		$this->template->content = View::factory('admin/auth/login')
			->bind('post', $post)
			->bind('errors', $errors);

		$post = Validate::factory($_POST)
			->filter(TRUE, 'trim')
			->rule('username', 'not_empty')
			->rule('password', 'not_empty')
			->callback('username', array($this, 'check_username'));

		if ($post->check())
		{
			if ($this->a1->login($post['username'], $post['password'],
				! empty($post['remember'])))
			{
				Kohana::$log->add('ACCESS', 'Successful login made with username, '
					.$post['username']);
				Message::instance()->info(Kohana::message('a2', 'login.success'),
					array(':name' => $post['username']));

				// If external request, redirect to referring URL or admin main
				if ( ! $this->_internal)
				{
					// Get referring URI, if any
					$referrer = $this->session->get('referrer')
						? $this->session->get('referrer')
						: Route::get('admin')->uri();
					$this->session->delete('referrer');

					$this->request->redirect($referrer);
				}
			}
			else
			{
				Kohana::$log->add('ACCESS', 'Unsuccessful login attempt made with username, '
					.$post['username']);
				$post->error('password', 'incorrect');
			}
		}

		$errors = $post->errors('admin');
	}

	/**
	 * Perform user logout
	 */
	public function action_logout() {
		Kohana::$log->add(Kohana::DEBUG, 'Executing Controller_Auth::action_logout');
		$this->a1->logout();

		Kohana::$log->add('ACCESS', 'Successful logout made by user.');
		Message::instance()->info(Kohana::message('a2', 'logout.success'));

		if ( ! $this->_internal)
		{
			$this->request->redirect( Route::get('admin')->uri() );
		}
	}

	/**
	 * Username callback to check if username is valid
	 */
	public function check_username(Validate $array, $field)
	{
		$exists = (bool) DB::select(array('COUNT("*")', 'total_count'))
			->from('users')
			->where('username', '=', $array[$field])
			->execute()->get('total_count');

		if ( ! $exists)
			$array->error($field, 'not_found', array($array[$field]));
	}

}

