<?php defined('SYSPATH') or die('No direct script access.');

/**
 * Base Admin Controller
 *
 * @package     Admin
 * @category    Base
 * @author      Kyle Treubig
 * @copyright   (c) 2010 Kyle Treubig
 * @license     MIT
 */
abstract class Controller_Admin_Base extends Controller_Template_Admin {

	/**
	 * @var array   Action-to-privilege ACL map
	 */
	protected $_acl_map = array('default' => NULL);

	/**
	 * @var array   Actions requiring ACL checking
	 */
	protected $_acl_required = array();

	/**
	 * @var mixed   ACL resource
	 */
	protected $_resource = 'resource';

	/**
	 * @var array   Actions requiring a loaded resource
	 */
	protected $_resource_required = array();

	/**
	 * @var array   Actions accessible as internal-only
	 */
	protected $_internal_only = array();

	/**
	 * @var boolean Internal or external request
	 */
	protected $_internal = FALSE;

	/**
	 * @var array   Action-to-layout view map
	 */
	protected $_view_map = array('default' => 'admin/layout/narrow_column');

	/**
	 * Sets up admin framework controllers
	 *
	 * - Redirects invalid internal-only access requests to the admin main
	 * - Loads resources if required, and redirects if invalid
	 * - Performs ACL checking, and redirects if denied
	 */
	public function before() {
		parent::before();

		// Set common variables
		$this->a2 = A2::instance('auth');
		$this->a1 = $this->a2->a1;
		$this->session = Session::instance();

		// Check if internal request
		if ($this->request !== Request::instance() OR Request::$is_ajax)
		{
			$this->_internal = TRUE;
		}

		// Check if internal-only request
		if (in_array($this->request->action, $this->_internal_only)
			AND ! $this->_internal)
		{
			Kohana::$log->add(Kohana::INFO, 'Attempt to access internal URL, '
				.$this->request->uri.', externally');
			Request::instance()->redirect( Route::get('admin')->uri() );
		}

		// Perform resource loads and ACL check
		try
		{
			if (in_array($this->request->action, $this->_resource_required))
			{
				$this->_load_resource();
			}

			if ($this->_acl_required === 'all' OR in_array($this->request->action, $this->_acl_required))
			{
				$privilege = isset($this->_acl_map[$this->request->action])
					? $this->_acl_map[$this->request->action]
					: $this->_acl_map['default'];
				$this->a2->allowed($this->_resource, $privilege, TRUE);
			}
		}
		catch (A2_Exception $ae)
		{
			// Redirect to login form if not logged in
			if ( ! $user = $this->a2->get_user())
			{
				$this->session->set('referrer', Request::instance()->uri);
				Message::instance()->error(Kohana::message('a2', 'login.required'));
				$this->request->redirect( Route::get('admin/auth')->uri() );
			}

			Kohana::$log->add('ACCESS', 'Failed attempt to access resource, '
				.$this->_resource.', by user, '.$user->username.', with url, '
				.$this->request->uri);

			Message::instance()->error($ae->getMessage(),
				array(':resource' => $this->_resource));

			// If internal request, redirect to denied action
			if ($this->_internal)
			{
				$this->request->action = 'denied';
			}
			else
			{
				// If controller-level access is denied, redirect to admin main
				if ($this->request->action == 'index')
				{
					$this->request->redirect( Route::get('admin')->uri());
				}
				// Else action-level access is denied, redirect to default action
				else
				{
					$this->request->redirect( $this->request->uri(
						array('action' => 'index', 'id' => NULL)) );
				}
			}
		}
		catch (Kohana_Exception $ke)
		{
			// Catch 404 exceptions triggered by invalid resource loads
			if ($ke->getCode() == 404)
			{
				Message::instance()->error($ke->getMessage());
				$this->request->redirect( $this->request->uri(
					array('action' => '', 'id' => NULL)) );
			}
			else
			{
				throw $ke;
			}
		}
	}

	/**
	 * Handles internal/external request-specific view settings
	 */
	public function after() {
		$content = $this->template->content;

		// If external request, insert into layout template
		if ( ! $this->_internal)
		{
			$view = isset($this->_view_map[$this->request->action])
				? $this->_view_map[$this->request->action]
				: $this->_view_map['default'];
			$this->template->content = View::factory($view)
				->set('menu', $this->_menu())
				->set('content', $content);
		}
		// Else append current info/error messages to internal response
		// and replace template with content
		else
		{
			$messages = Message::instance()->get();
			$this->template = $messages.$content;
		}

		parent::after();
	}

	/**
	 * Load a specific resource
	 */
	protected function _load_resource() { }

	/**
	 * Generate menu
	 */
	protected function _menu() { }

	/**
	 * Controller-level access denial message for
	 * internal requests
	 */
	public function action_denied() { }

}	// End of Controller_Admin_Base

