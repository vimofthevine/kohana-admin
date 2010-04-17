<?php defined('SYSPATH') OR die('No direct script access.');

/**
 * @package     Controller
 * @author      Kyle Treubig
 * @copyright   (c) 2010 Kyle Treubig
 * @license     MIT
 */
class Controller_Admin_Users extends Controller_Template_Admin {

	/**
	 * Register controller as an admin controller
	 */
	public function before() {
		parent::before();

		$this->restrict('user', 'manage');
		unset($this->template->menu->menu['Users'][0]);
	}

	/**
	 * Default action to list
	 */
	public function action_index() {
		$this->action_list();
	}

	/**
	 * Generate menu for user management
	 */
	private function menu() {
		return View::factory('admin/users/menu');
	}

	/**
	 * Display menu for user management
	 */
	public function action_menu() {
		if ( ! $this->internal_request)
		{
			Request::instance()->redirect( Route::get('admin_main')->uri('controller'=>'users') );
		}

		$this->template->content = $this->menu();
	}

	/**
	 * Display list of users
	 */
	public function action_list() {
		Kohana::$log->add(Kohana::DEBUG, 'Executing Controller_Users::action_list');

		$users = Sprig::factory('user')->load(NULL, FALSE);

		// Check if there are any users to display
		if (count($users) == 0)
		{
			$hmvc = View::factory('admin/users/hmvc/none');

			$view = View::factory('admin/users/list')
				->set('menu', $this->menu())
				->set('list', $hmvc);

			$this->template->content = $this->internal_request ? $hmvc : $view;
			return;
		}

		// Create user list
		$grid = new Grid;
		$grid->column()->field('id')->title('ID');
		$grid->column('action')->title('Username')->display_field('username')
			->action(Route::get('admin_main')->uri(array('controller'=>'users', 'action'=>'view')));
		$grid->column()->field('role')->title('Role');
		$grid->column()->field('email')->title('Email');
		$grid->column('action')->title('Actions')->text('Edit')->class('edit')
			->action(Route::get('admin_main')->uri(array('controller'=>'users', 'action'=>'edit')));
		$grid->column('action')->title('')->text('Delete')->class('delete')
			->action(Route::get('admin_main')->uri(array('controller'=>'users', 'action'=>'delete')));
		$grid->data($users);

		// Setup HMVC view with data
		$hmvc = View::factory('admin/users/hmvc/list')
			->set('grid', $grid);

		// Setup template view
		$view = View::factory('admin/users/list')
			->set('menu', $this->menu())
			->set('list', $hmvc);

		// Set request response
		$this->template->content = $this->internal_request ? $hmvc : $view;
	}

	/**
	 * Display details for a user
	 */
	public function action_view() {
		Kohana::$log->add(Kohana::DEBUG, 'Executing Controller_Users::action_view');

		$id = Request::instance()->param('id');
		$user = Sprig::factory('user', array('id'=>$id))->load();

		// If user is invalid, return to list
		if ( ! $user->loaded())
		{
			$message = __('That user does not exist.');

			// Return message if an ajax request
			if (Request::$is_ajax)
			{
				$this->template->content = $message;
			}
			// Else set flash message and redirect
			else
			{
				Message::instance()->error($message);
				Request::instance()->redirect( Route::get('admin_main')->uri(array('controller'=>'users')) );
			}
		}

		// Restrict access
		if ( ! $this->a2->allowed($user, 'view'))
		{
			$message = __('You do not have permission to view :name\'s details.', array(':name'=>$user->username));

			// Return message if an ajax request
			if (Request::$is_ajax)
			{
				$this->template->content = $message;
			}
			// Else set flash message and redirect
			else
			{
				Message::instance()->error($message);
				Request::instance()->redirect( Route::get('admin_main')->uri(array('controller'=>'users')) );
			}
		}

		// Setup HMVC view with data
		$hmvc = View::factory('admin/users/hmvc/view')
			->set('user', $user);

		// Setup template view
		$view = View::factory('admin/users/view')
			->set('menu', $this->menu())
			->set('details', $hmvc);

		// Set request response
		$this->template->content = $this->internal_request ? $hmvc : $view;
	}

	/**
	 * Create a new user
	 */
	public function action_new() {
		Kohana::$log->add(Kohana::DEBUG, 'Executing Controller_Users::action_new');

		// Restrict access
		if ( ! $this->a2->allowed('user', 'create'))
		{
			$message = __('You do not have permission to create new users.');

			// Return message if an ajax request
			if (Request::$is_ajax)
			{
				$this->template->content = $message;
			}
			// Else set flash message and redirect
			else
			{
				Message::instance()->error($message);
				Request::instance()->redirect( Route::get('admin_main')->uri(array('controller'=>'users')) );
			}
		}

		$user = Sprig::factory('user')->values($_POST);

		try
		{
			$user->create();
			$message = __('The user, :name, has been created.', array(':name'=>$user->username));

			// Return message if an ajax request
			if (Request::$is_ajax)
			{
				$this->template->content = $message;
			}
			// Else set flash message and redirect
			else
			{
				Message::instance()->info($message);
				Request::instance()->redirect( Route::get('admin_main')->uri(array('controller'=>'users')) );
			}
		}
		catch (Validate_Exception $e)
		{
			// Setup HMVC view with data
			$hmvc = View::factory('admin/users/hmvc/form')
				->set('legend', __('Create User'))
				->set('submit', __('Create'))
				->set('user', $user)
				->set('errors', count($_POST) ? $e->array->errors('admin') : array() );

			// Setup template view
			$view = View::factory('admin/users/form')
				->set('menu', $this->menu())
				->set('form', $hmvc);

			// Set request response
			$this->template->content = $this->internal_request ? $hmvc : $view;
		}
	}

	/**
	 * Edit user details
	 */
	public function action_edit() {
		Kohana::$log->add(Kohana::DEBUG, 'Executing Controller_Users::action_edit');

		$id = Request::instance()->param('id');
		$user = Sprig::factory('user', array('id'=>$id))->load();

		// If user is invalid, return to list
		if ( ! $user->loaded())
		{
			$message = __('That user does not exist.');

			// Return message if an ajax request
			if (Request::$is_ajax)
			{
				$this->template->content = $message;
			}
			// Else set flash message and redirect
			else
			{
				Message::instance()->error($message);
				Request::instance()->redirect( Route::get('admin_main')->uri(array('controller'=>'users')) );
			}
		}

		// Restrict access
		if ( ! $this->a2->allowed($user, 'edit'))
		{
			$message = __('You do not have permission to modify :name.', array(':name'=>$user->username));

			// Return message if an ajax request
			if (Request::$is_ajax)
			{
				$this->template->content = $message;
			}
			// Else set flash message and redirect
			else
			{
				Message::instance()->error($message);
				Request::instance()->redirect( Route::get('admin_main')->uri(array('controller'=>'users')) );
			}
		}

		// Restrict promotion (change in role)
		if ( ! $this->a2->allowed($user, 'promote'))
		{
			isset($_POST['role']) ? $_POST['role'] = $user->role : NULL;
		}

		// Restrict renaming
		if ( ! $this->a2->allowed($user, 'rename'))
		{
			isset($_POST['username']) ? $_POST['username'] = $user->username : NULL;
		}

		// Unset password if not changing it
		if (empty($_POST['password']))
		{
			unset($_POST['password']);
			unset($_POST['password_confirm']);
		}

		$user->values($_POST);

		// Setup HMVC view with data
		$hmvc = View::factory('admin/users/hmvc/form')
			->set('legend', __('Modify User'))
			->set('submit', __('Save'))
			->set('user', $user);

		if (count($_POST))
		{
			try
			{
				$user->update();
				$message = __('The user, :name, has been modified.', array(':name'=>$user->username));

				// Return message if an ajax request
				if (Request::$is_ajax)
				{
					$this->template->content = $message;
				}
				// Else set flash message and redirect
				else
				{
					Message::instance()->info($message);
					Request::instance()->redirect( Route::get('admin_main')->uri(array('controller'=>'users')) );
				}
			}
			catch (Validate_Exception $e)
			{
				$hmvc->errors = count($_POST) ? $e->array->errors('admin') : array();
			}
		}

		// Setup template view
		$view = View::factory('admin/users/form')
			->set('menu', $this->menu())
			->set('form', $hmvc);

		// Set request response
		$this->template->content = $this->internal_request ? $hmvc : $view;
	}

	/**
	 * Edit a user's own profile
	 */
	public function action_profile() {
		Kohana::$log->add(Kohana::DEBUG, 'Executing Controller_Users::action_profile');

		$user = $this->a1->get_user();

		if ($user !== FALSE)
		{
			Request::instance()->redirect( Route::get('admin_main')
				->uri(array(
					'controller' => 'users',
					'action'     => 'edit',
					'id'         => $user->id,
				))
			);
		}
		else
		{
			Message::instance()->error('You must be logged in to do that.');
			Request::instance()->redirect( Route::get('admin_auth')
				->uri(array('action'=>'login'))
			);
		}
	}

	/**
	 * Delete a user
	 */
	public function action_delete() {
		Kohana::$log->add(Kohana::DEBUG, 'Executing Controller_Users::action_delete');

		// If deletion is not desired, redirect to list
		if (isset($_POST['no']))
		{
			Request::instance()->redirect( Route::get('admin_main')->uri(array('controller'=>'users')) );
		}

		$id = Request::instance()->param('id');
		$user = Sprig::factory('user', array('id'=>$id))->load();
		$name = $user->username;

		// If user is invalid, return to list
		if ( ! $user->loaded())
		{
			$message = __('That user does not exist.');

			// Return message if an ajax request
			if (Request::$is_ajax)
			{
				$this->template->content = $message;
			}
			// Else set flash message and redirect
			else
			{
				Message::instance()->error($message);
				Request::instance()->redirect( Route::get('admin_main')->uri(array('controller'=>'users')) );
			}
		}

		// Restrict access
		if ( ! $this->a2->allowed($user, 'delete'))
		{
			$message = __('You do not have permission to delete :name.', array(':name'=>$name));

			// Return message if an ajax request
			if (Request::$is_ajax)
			{
				$this->template->content = $message;
			}
			// Else set flash message and redirect
			else
			{
				Message::instance()->error($message);
				Request::instance()->redirect( Route::get('admin_main')->uri(array('controller'=>'users')) );
			}
		}

		// If deletion is confirmed
		if (isset($_POST['yes']))
		{
			try
			{
				$user->delete();
				$message = __('The user, :name, has been deleted.', array(':name'=>$name));

				// Return message if an ajax request
				if (Request::$is_ajax)
				{
					$this->template->content = $message;
				}
				// Else set flash message and redirect
				else
				{
					Message::instance()->info($message);
					Request::instance()->redirect( Route::get('admin_main')->uri(array('controller'=>'users')) );
				}
			}
			catch (Exception $e)
			{
				Kohana::$log->add(Kohana::ERROR, 'Error occured deleting user, id='.$user->id.', '.$e->getMessage());
				$message = __('An error occured deleting user, :name.', array(':name'=>$name));

				// Return message if an ajax request
				if (Request::$is_ajax)
				{
					$this->template->content = $message;
				}
				// Else set flash message and redirect
				else
				{
					Message::instance()->error($message);
					Request::instance()->redirect( Route::get('admin_main')->uri(array('controller'=>'users')) );
				}
			}
		}

		// Setup HMVC view with data
		$hmvc = View::factory('admin/users/hmvc/delete')
			->set('user', $user);

		// Setup template view
		$view = View::factory('admin/users/delete')
			->set('menu', $this->menu())
			->set('confirm', $hmvc);

		// Set request response
		$this->template->content = $this->internal_request ? $hmvc : $view;
	}

}	// End of Controller_Admin_Users

