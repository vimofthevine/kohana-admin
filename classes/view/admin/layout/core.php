<?php defined('SYSPATH') or die('No direct script access.');

/**
 * Core layout view for the admin module
 *
 * @package     Admin
 * @category    Views
 * @author      Kyle Treubig
 * @copyright   (C) 2010 Kyle Treubig
 * @license     MIT
 */
abstract class View_Admin_Layout_Core extends Kostache {

	/**
	 * @var boolean Whether to inject into a grid layout
	 */
	public $use_layout = TRUE;

	/**
	 * @var string  Title for the administrative application
	 */
	public $title = 'Administrative Control Panel';

	/**
	 * @var object  A2 instance
	 */
	public $a2 = NULL;

	/**
	 * @var object  Request object
	 */
	public $request = NULL;

	/**
	 * @var string  The template file for the admin module layout
	 */
	protected $_layout = 'admin/layout';

	/**
	 * @var string  The grid layout template for the view
	 */
	protected $_grid = 'admin/layout/narrow';

	/**
	 * @var array   Array of stylesheets
	 */
	public $_stylesheets = array(
		'theme/css/960.css'        => 'screen',
		'theme/css/template.css'   => 'screen',
		'theme/css/colour.css'     => 'screen',
		'css/admin/tpd_custom.css' => 'screen',
	);

	/**
	 * @var array   Array of scripts
	 */
	public $_scripts = array();

	/**
	 * Get the stylesheets for the layout
	 *
	 * @return  Array of stylesheets
	 */
	public function stylesheets()
	{
		$stylesheets = array();

		foreach ($this->_stylesheets as $style => $media)
		{
			$url = Route::get('admin/media')->uri(array('file' => $style));
			$stylesheets[] = HTML::style($url, array('media' => $media));
		}

		return implode("\n", $stylesheets);
	}

	/**
	 * Get the scripts for the layout
	 *
	 * @return  Array of scripts
	 */
	public function scripts()
	{
		$scripts = array();

		foreach ($this->_scripts as $script)
		{
			$scripts[] = HTML::script($script);
		}

		return implode("\n", $scripts);
	}

	/**
	 * Get the top-level navigation items
	 * to display as the main menu for
	 * the Admin App
	 *
	 * @return  Array of navigation links
	 */
	public function primary_nav_items()
	{
		$items = array();

		// Add dashboard link
		$url  = Route::get('admin')->uri();
		$text = __('Home');
		$items[] = array(
			'url'    => $url,
			'text'   => $text,
			'slug'   => URL::title($text),
			'link'   => HTML::anchor($url, $text),
			'active' => $this->is_active('admin'),
		);

		// Add extension groups
		$groups = Admin::extensions();

		foreach ($groups as $group => $extensions)
		{
			// Get the URL for the first extension
			$url = Route::get('admin/external')->uri(array('extension' => $extensions[0]));

			// Get the normalized name
			$name = __(ucfirst($group));

			// Add the extension to the navigation
			$items[] = array(
				'url'    => $url,
				'text'   => $name,
				'slug'   => URL::title($name),
				'link'   => HTML::anchor($url, $name),
				'active' => $this->is_active($group),
			);
		}

		// Add login/logout link
		if ($this->logged_in())
		{
			// Get logout URL
			$url = Route::get('admin/auth')->uri(array('action' => 'logout'));

			// Get the normalized text
			$text = __('Logout');

			// Add logout to the navigation
			$items[] = array(
				'url'    => $url,
				'text'   => $text,
				'slug'   => URL::title($text),
				'link'   => HTML::anchor($url, $text),
				'active' => $this->is_active('admin/auth'),
			);
		}
		else
		{
			// Get login URL
			$url = Route::get('admin/auth')->uri();

			// Get the normalized text
			$text = __('Login');

			// Add login to the navigation
			$items[] = array(
				'url'    => $url,
				'text'   => $text,
				'slug'   => URL::title($text),
				'link'   => HTML::anchor($url, $text),
				'active' => $this->is_active('admin/auth'),
			);
		}

		return $items;
	}
	/**
	 * Get info message, if there is any
	 *
	 * @return  The info message
	 */
	public function info_message()
	{
		if (empty($this->_messages))
		{
			$this->_messages = Admin::messages();
		}

		if (isset($this->_messages[Admin::INFO]))
			return implode("<br />", $this->_messages[Admin::INFO]);
		else
			return NULL;
	}

	/**
	 * Get error message, if there is any
	 *
	 * @return  The error message
	 */
	public function error_message()
	{
		if (empty($this->_messages))
		{
			$this->_messages = Admin::messages();
		}

		if (isset($this->_messages[Admin::ERROR]))
			return implode("<br />", $this->_messages[Admin::ERROR]);
		else
			return NULL;
	}

	/**
	 * Get the current extension, action, and group
	 */
	protected function _get_extension()
	{
		// Get current request
		if ($this->request == NULL)
		{
			$this->request = Request::instance();
		}

		// Get current route
		$route = Route::name($this->request->route);

		// Get current route
		if ($route == 'admin/external')
		{
			// Get current extension and route
			$this->_extension = $this->request->param('extension');
			$this->_group = Admin::group($this->_extension);
		}
		else
		{
			$this->_extension = NULL;
			$this->_group = $route;
		}

		$this->_action = $this->request->action;
	}

	/**
	 * Get the current group
	 *
	 * @return  The current group
	 */
	public function group()
	{
		// If group not yet determined
		if (empty($this->_group))
		{
			$this->_get_extension();
		}

		return $this->_group;
	}

	/**
	 * Get the current extension
	 *
	 * @return  The current extension
	 */
	public function extension()
	{
		// If extension not yet determined
		if (empty($this->_extension))
		{
			$this->_get_extension();
		}

		return $this->_extension;
	}

	/**
	 * Get the current action
	 *
	 * @return  The current action
	 */
	public function action()
	{
		// If action not yet determined
		if (empty($this->_action))
		{
			$this->_get_extension();
		}

		return $this->_action;
	}

	/**
	 * Determine if there are any related extensions
	 * to the current extension (if an extension is being
	 * displayed) that require a secondary menu to be displayed
	 *
	 * @return  True if there are related extensions
	 */
	public function has_group_extensions()
	{
		return count($this->group_extensions()) > 0;
	}

	/**
	 * Get the heading for the secondary navigation
	 *
	 * @return  The secondary navigation heading
	 */
	public function group_mgmt_heading()
	{
		return __(':group Management', array(':group' => ucfirst($this->group())));
	}

	/**
	 * Determine the secondary navigation items
	 * (related extensions belonging to the same
	 * group as the current/active extensions)
	 *
	 * @return  Array of secondary navigation items
	 */
	public function group_extensions()
	{
		// If items not yet determined
		if ( ! isset($this->_group_extensions))
		{
			// Create items array
			$this->_group_extensions = array();

			// Get registered extensions
			$groups = Admin::extensions();

			if (isset($groups[$this->group()]))
			{
				foreach ($groups[$this->group()] as $extension)
				{
					// Get the URL for the extensions
					$url = Route::get('admin/external')->uri(array('extension' => $extension));

					// Get the normalized text
					$text = __(ucfirst($extension));

					// Get the link
					$link = HTML::anchor($url, $text);

					// Add the extension to the navigation
					$this->_group_extensions[] = array(
						'url'  => $url,
						'text' => $text,
						'link' => $link,
					);
				}
			}
		}

		return $this->_group_extensions;
	}

	/**
	 * Get the registered navigation views
	 * for the current extension, action, and group,
	 * if any
	 */
	public function registered_navs()
	{
		// Get registered navs matching the current environment
		$navs = Admin::nav($this->extension(), $this->action(), $this->group());

		$return = '';

		foreach ($navs as $nav)
		{
			$return .= Kostache::factory($nav)->render();
		}

		return $return;
	}

	/**
	 * Check if the given group is the current group
	 *
	 * @param   string  The extension group to check
	 * @return  True if the given group is active
	 */
	protected function is_active($group = '')
	{
		// Get current request
		if ($this->request == NULL)
		{
			$this->request = Request::instance();
		}

		// Get current route
		$route = Route::name($this->request->route);

		// If external extension route
		if ($route == 'admin/external')
		{
			// Check if group matches
			$extension = $this->request->param('extension');
			return $group == Admin::group($extension);
		}
		else
		{
			return $route == $group;
		}
	}

	/**
	 * Check if user is currently logged in
	 *
	 * @return  True if the user is logged in, else false
	 */
	protected function logged_in()
	{
		if ($this->a2 == NULL)
		{
			$this->a2 = A2::instance(Kohana::config('admin.a2'));
		}

		return $this->a2->logged_in();
	}

	/**
	 * Override render method to insert into layout template
	 * if desired, and to inject into a grid layout
	 */
	public function render($template = NULL, $view = NULL, $partials = NULL)
	{
		// If layout injection desired
		if ($this->use_layout)
		{
			// Override the template location to match kohana's conventions
			if ( ! $this->_template)
			{
				$foo = explode('_', get_class($this));
				array_shift($foo);
				$this->_template = strtolower(implode(DIRECTORY_SEPARATOR, $foo));
			}

			$this->_partials += array(
				'contents' => $this->_template,
				'body'     => $this->_grid,
			);

			// Make the layout template the child class's template
			$this->_template = $this->_layout;
		}

		return parent::render($template, $view, $partials);
	}

}	// End of View_Admin_Layout_Core
