<?php defined('SYSPATH') OR die('No direct script access.');

/**
 * @package     Admin
 * @category    Controller
 * @author      Kyle Treubig
 * @copyright   (c) 2010 Kyle Treubig
 * @license     MIT
 */
class Controller_Admin_Users extends Controller_Admin {

	protected $_resource = 'user';

	protected $_acl_map = array(
		'view'    => 'view',
		'new'     => 'create',
		'edit'    => 'edit',
		'delete'  => 'delete',
		'default' => 'manage',
	);

	protected $_acl_required = 'all';

	protected $_view_map = array(
		'new'     => 'admin/layout/narrow_column_with_menu',
		'edit'    => 'admin/layout/narrow_column_with_menu',
		'default' => 'admin/layout/wide_column_with_menu',
	);

	protected $_resource_required = array('view', 'edit', 'delete');

	protected $_current_nav = 'admin/users';

	/**
	 * Generate menu for user management
	 */
	protected function _menu() {
		return View::factory('admin/users/menu');
	}

	/**
	 * Load a specified user
	 */
	protected function _load_resource() {
		$id = $this->request->param('id', 0);
		$this->_resource = Sprig::factory('user', array('id'=>$id))->load();
		if ( ! $this->_resource->loaded())
		{
			throw new Kohana_Exception('That user does not exist.', NULL, 404);
		}
	}

	/**
	 * Redirect index action to list
	 */
	public function action_index() {
		$this->request->redirect( $this->request->uri(
			array('action' => 'list')), 301);
	}

	/**
	 * Display list of users
	 */
	public function action_list() {
		Kohana::$log->add(Kohana::DEBUG, 'Executing Controller_Users::action_list');
		$this->template->content = View::factory('admin/users/list')
			->bind('users', $users);
		$users = Sprig::factory('user')->load(NULL, FALSE);
	}

	/**
	 * Display details for a user
	 */
	public function action_view() {
		Kohana::$log->add(Kohana::DEBUG, 'Executing Controller_Users::action_view');
		$this->template->content = View::factory('admin/users/view')
			->bind('user', $this->_resource);
	}

	/**
	 * Create a new user
	 */
	public function action_new() {
		Kohana::$log->add(Kohana::DEBUG, 'Executing Controller_Users::action_new');
		$this->template->content = View::factory('admin/users/form')
			->set('legend', __('Create User'))
			->set('submit', __('Create'))
			->bind('user', $user)
			->bind('errors', $errors);

		$user = Sprig::factory('user')->values($_POST);

		if ($_POST)
		{
			try
			{
				$user->create();

				Message::instance()->info('The user, :name, has been created.',
					array(':name' => $user->username));

				if ( ! $this->_internal)
					$this->request->redirect( $this->request->uri(array('action'=>'list')) );
			}
			catch (Validate_Exception $e)
			{
				$errors = $e->array->errors('admin');
			}
		}
	}

	/**
	 * Edit user details
	 */
	public function action_edit() {
		Kohana::$log->add(Kohana::DEBUG, 'Executing Controller_Users::action_edit');
		$this->template->content = View::factory('admin/users/form')
			->set('legend', __('Modify User'))
			->set('submit', __('Save'))
			->bind('user', $this->_resource)
			->bind('errors', $errors);

		// Bind locally
		$user = & $this->_resource;

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

		if ($_POST)
		{
			$user->values($_POST);

			try
			{
				$user->update();

				Message::instance()->info('The user, :name, has been modified.',
					array(':name' => $user->username));

				if ( ! $this->_internal)
					$this->request->redirect( $this->request->uri(array('action'=>'list')) );
			}
			catch (Validate_Exception $e)
			{
				$errors = $e->array->errors('admin');
			}
		}
	}

	/**
	 * Edit a user's own profile
	 */
	public function action_profile() {
		Kohana::$log->add(Kohana::DEBUG, 'Executing Controller_Users::action_profile');

		$user = $this->a1->get_user();

		if ($user !== FALSE)
		{
			$this->request->redirect( $this->request->uri(array(
				'action'     => 'edit',
				'id'         => $user->id,
			)) );
		}
		else
		{
			Message::instance()->error('You must be logged in to do that.');
			$this->request->redirect( Route::get('admin/auth')
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
			$this->request->redirect( $this->request->uri(array('action'=>'list')) );

		$this->template->content = View::factory('admin/users/delete')
			->bind('user', $this->_resource);

		// Bind locally
		$user = & $this->_resource;
		$name = $user->username;

		// If deletion is confirmed
		if (isset($_POST['yes']))
		{
			try
			{
				$user->delete();
				Message::instance()->info('The user, :name, has been deleted.',
					array(':name' => $name));

				if ( ! $this->_internal)
					$this->request->redirect( $this->request->uri(array('action'=>'list')) );
			}
			catch (Exception $e)
			{
				Kohana::$log->add(Kohana::ERROR, 'Error occured deleting user, id='.$user->id.', '.$e->getMessage());
				Message::instance()->error('An error occured deleting user, :name.',
					array(':name' => $name));

				if ( ! $this->_internal)
					$this->request->redirect( $this->request->uri(array('action'=>'list')) );
			}
		}
	}

}	// End of Controller_Admin_Users

