<?php defined('SYSPATH') OR die('No direct script access.');

/**
 * @package     Admin
 * @author      Kyle Treubig
 * @copyright   (c) 2010 Kyle Treubig
 * @license     MIT
 */
abstract class Controller_Template_Admin extends Controller_Template {

	/**
	 * @var Template file
	 */
	public $template = 'admin/themes/default/template';

	/**
	 * @var Admin configuration
	 */
	protected $_config;

	/**
	 * @var Error message
	 */
	public $error = NULL;

	/**
	 * @var Info message
	 */
	public $message = NULL;

	/**
	 * Configure admin controller
	 */
	public function before() {
		parent::before();

		// Load admin config
		$this->_config = Kohana::config('admin');

		// Set common variables
		$this->a2 = A2::instance('auth');
		$this->a1 = $this->a2->a1;
		$this->session = Session::instance();

		if ($this->auto_render)
		{
			// Prepare templates
			$this->template          = View::factory($this->_config['template'].'/template');
			$this->template->header  = View::factory($this->_config['template'].'/header');
			$this->template->menu    = View::factory($this->_config['template'].'/menu');
			$this->template->message = View::factory($this->_config['template'].'/message');
			$this->template->footer  = View::factory($this->_config['template'].'/footer');

			// Bind menu items
			$this->template->menu->bind('menu', $this->_config['menu']);

			// Prepare media arrays
			$this->template->styles = array();
			$this->template->scripts = array();

			// Grab error/info messages
			$this->template->message->msg = $this->session->get('flash_msg');
			$this->template->message->err = $this->session->get('flash_err');
			// Delete session variables
			$this->session->delete('flash_msg');
			$this->session->delete('flash_err');
		}
	}

	/**
	 * Perform pre-render actions on admin controller
	 */
	public function after() {
		if ($this->auto_render)
		{
			$styles = array();
			$scripts = array();

			$this->template->header->styles  = array_merge($this->template->styles, $styles);
			$this->template->header->scripts = array_merge($this->template->scripts, $scripts);
		}

		Kohana::$log->add(Kohana::DEBUG, 'Message added, '.$this->message);

		parent::after();
	}

	/**
	 * Save an info message in the session to display on the
	 * next page load.
	 *
	 * @param   string  info message
	 */
	protected function message($msg, array $values = NULL, $lang = 'en-us') {
		$this->session->set('flash_msg', __($msg, $values, $lang));
	}

	/**
	 * Save an error message in the session to display on the
	 * next page load.
	 *
	 * @param   string  error message
	 */
	protected function error($msg, array $values = NULL, $lang = 'en-us') {
		$this->session->set('flash_err', __($msg, $values, $lang));
	}

	/**
	 * Perform an ACL check against a resource and privilege.
	 * <ul>
	 *  <li>If the current user is not allowed access, redirect
	 *    to the main page.</li>
	 *  <li>If the current user is not logged in, redirect to
	 *    the login page, setting the referrer url.</li>
	 * </ul>
	 *
	 * @param   string  An ACL resource
	 * @param   string  Privilege on the resource
	 * @param   string  [optional] error message string
	 * @param   string  Redirect URL
	 */
	protected function restrict($resource=NULL, $privilege=NULL, $message=NULL , $url=NULL) {
		if ( ! $this->_config['acl_enabled'])
			return;

		if ( ! $this->a2->allowed($resource, $privilege))
		{
			if ($this->a2->get_user() === FALSE)
			{
				$this->session->set('referrer', Request::instance()->uri);
				$this->error('You must be logged in to do that.');
				Request::instance()->redirect( Route::get('admin_auth')->uri(array('action'=>'login')) );
			}
			else
			{
				$error = isset($message) ? $message : 'You do not have permission to do that.';
				$this->error($error);
				$url = isset($url) ? $url : Route::get('admin')->uri();
				Request::instance()->redirect($url);
			}
		}
	}

	/**
	 * Add items to the admin menu template
	 *
	 * @param   string  the menu item key
	 * @param   string  an array for the menu item
	 */
	protected function add_menu($key, $menu) {
		if (array_key_exists($key, $this->_config['menu']))
		{
			$this->_config['menu'][$key][1] = $menu;
		}
	}

}	// End of Controller_Template_Admin

