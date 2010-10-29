<?php defined('SYSPATH') or die('No direct script access.');

/**
 * Top-level navigation view
 *
 * @package     Admin
 * @category    Views
 * @author      Kyle Treubig
 * @copyright   (C) 2010 Kyle Treubig
 * @license     MIT
 */
class View_Admin_Nav_Top extends Kostache {

	/**
	 * @var object  A2 instance
	 */
	public $a2 = NULL;

	/**
	 * @var object  Request object
	 */
	public $request = NULL;

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
	 * Get the navigation items to display
	 * as the main menu for the Admin App
	 *
	 * @return  Array of navigation links
	 */
	public function items()
	{
		$items = array();

		// Add dashboard link
		$items[__('Home')] = array(
			'url'    => Route::get('admin')->uri(),
			'active' => $this->is_active('admin'),
		);

		// Add extension groups
		$groups = Admin::extensions();

		foreach ($groups as $group => $extensions)
		{
			// Get the URL for the first extension
			$url = Route::get('admin/external')->uri(array('extension' => $extensions[0]));

			// Add the extension to the navigation
			$items[__(ucfirst($group))] = array(
				'url'    => $url,
				'active' => $this->is_active($group),
			);
		}

		// Add login/logout link
		if ($this->logged_in())
		{
			// Get logout URL
			$url = Route::get('admin/auth')->uri(array('action' => 'logout'));

			// Add logout to the navigation
			$items[__('Logout')] = array(
				'url'    => $url,
				'active' => $this->is_active('admin/auth'),
			);
		}
		else
		{
			// Get login URL
			$url = Route::get('admin/auth')->uri();

			// Add login to the navigation
			$items[__('Login')] = array(
				'url'    => $url,
				'active' => $this->is_active('admin/auth'),
			);
		}

		return $items;
	}

}	// End of View_Admin_Nav_Top
