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
		$this->add_menu('users', array(
			'create' => Route::get('admin_main')->uri(array('controller'=>'users', 'action'=>'new')),
			'edit profile' => Route::get('admin_main')->uri(array('controller'=>'users', 'action'=>'profile')),
		));
	}

	/**
	 * Default action to list
	 */
	public function action_index() {
		$this->action_list();
	}

	/**
	 * Display list of users
	 */
	public function action_list() {
		$users = Sprig::factory('user')->load(NULL, FALSE);

		if (count($users) == 0)
		{
			$this->template->content = new View('admin/users/none');
			return;
		}

		$grid = new Grid;
		$grid->column()->field('id')->title('ID');
		$grid->column('action')->title('Username')->display_field('username')
			->action(Route::get('admin_main')->uri(array('controller'=>'users', 'action'=>'view')));
		$grid->column()->field('role')->title('Role');
		$grid->column()->field('email')->title('Email');
		$grid->column('action')->title('Edit')->text('edit')
			->action(Route::get('admin_main')->uri(array('controller'=>'users', 'action'=>'edit')));
		$grid->column('action')->title('Del')->text('del')
			->action(Route::get('admin_main')->uri(array('controller'=>'users', 'action'=>'delete')));
		$grid->link()->text('Add User')
			->action(Route::get('admin_main')->uri(array('controller'=>'users', 'action'=>'new')));
		$grid->data($users);

		$this->template->content = new View('admin/users/list');
		$this->template->content->grid = $grid;
	}

	/**
	 * Display details for a user
	 */
	public function action_view() {
		$id = Request::instance()->param('id');
		$user = Sprig::factory('user', array('id'=>$id))->load();

		// If user is invalid, return to list
		if ( ! $user->loaded())
		{
			Message::instance()->error('That user does not exist.');
			Request::instance()->redirect( Route::get('admin_main')->uri(array('controller'=>'users')) );
		}

		// Restrict access
		if ( ! $this->a2->allowed($user, 'view'))
		{
			Message::instance()->error('You do not have permission to view :name\'s details.', array(':name'=>$user->username));
			Request::instance()->redirect( Route::get('admin_main')->uri(array('controller'=>'users')) );
		}

		$view = new View('admin/users/view');
		$view->user = $user;
		$this->template->content = $view;
	}

	/**
	 * Create a new user
	 */
	public function action_new() {
		// Restrict access
		if ( ! $this->a2->allowed('user', 'create'))
		{
			Message::instance()->error('You do not have permission to create new users.');
			Request::instance()->redirect( Route::get('admin_main')->uri(array('controller'=>'users')) );
		}

		$user = Sprig::factory('user')->values($_POST);

		try
		{
			$user->create();
			Message::instance()->info('The user, :name, has been created.', array(':name'=>$user->username));
			Request::instance()->redirect( Route::get('admin_main')->uri(array('controller'=>'users')) );
		}
		catch (Validate_Exception $e)
		{
			$view = new View('admin/users/form');
			$view->legend = __('Create User');
			$view->submit = __('Create');
			$view->user   = $user;
			$view->errors = count($_POST) ? $e->array->errors('admin') : array();

			$this->template->content = $view;
		}
	}

	/**
	 * Edit user details
	 */
	public function action_edit() {
		$id = Request::instance()->param('id');
		$user = Sprig::factory('user', array('id'=>$id))->load();

		// If user is invalid, return to list
		if ( ! $user->loaded())
		{
			Message::instance()->error('That user does not exist.');
			Request::instance()->redirect( Route::get('admin_main')->uri(array('controller'=>'users')) );
		}

		// Restrict access
		if ( ! $this->a2->allowed($user, 'edit'))
		{
			Message::instance()->error('You do not have permission to modify :name.', array(':name'=>$user->username));
			Request::instance()->redirect( Route::get('admin_main')->uri(array('controller'=>'users')) );
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
		$view = new View('admin/users/form');
		$view->legend = __('Modify User');
		$view->submit = __('Save');
		$view->user   = $user;

		if (count($_POST))
		{
			try
			{
				$user->update();
				Message::instance()->info('The user, :name, has been modified.', array(':name'=>$user->username));
				Request::instance()->redirect( Route::get('admin_main')->uri(array('controller'=>'users')) );
			}
			catch (Validate_Exception $e)
			{
				$view->errors = count($_POST) ? $e->array->errors('admin') : array();
			}
		}

		$this->template->content = $view;
	}

	/**
	 * Edit a user's own profile
	 */
	public function action_profile() {
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
		if (isset($_POST['no']))
		{
			Request::instance()->redirect( Route::get('admin_main')->uri(array('controller'=>'users')) );
		}

		$id = Request::instance()->param('id');
		$user = Sprig::factory('user', array('id'=>$id))->load();

		// If user is invalid, return to list
		if ( ! $user->loaded())
		{
			Message::instance()->error('That user does not exist.');
			Request::instance()->redirect( Route::get('admin_main')->uri(array('controller'=>'users')) );
		}

		$name = $user->username;

		// Restrict access
		if ( ! $this->a2->allowed($user, 'delete'))
		{
			Message::instance()->error('You do not have permission to delete :name.', array(':name'=>$name));
			Request::instance()->redirect( Route::get('admin_main')->uri(array('controller'=>'users')) );
		}

		if (isset($_POST['yes']))
		{
			try
			{
				$user->delete();
				Message::instance()->info('The user, :name, has been deleted.', array(':name'=>$name));
				Request::instance()->redirect( Route::get('admin_main')->uri(array('controller'=>'users')) );
			}
			catch (Exception $e)
			{
				Message::instance()->error('An error occured deleting user, :name.', array(':name'=>$name));
				Request::instance()->redirect( Route::get('admin_main')->uri(array('controller'=>'users')) );
			}
		}

		$view = new View('admin/users/delete');
		$view->user = $user;
		$this->template->content = $view;
	}

}	// End of Controller_Admin_Users

