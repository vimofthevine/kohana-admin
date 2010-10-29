<?php defined('SYSPATH') or die('No direct script access.');

/**
 * Second-level navigation view
 *
 * @package     Admin
 * @category    Views
 * @author      Kyle Treubig
 * @copyright   (C) 2010 Kyle Treubig
 * @license     MIT
 */
class View_Admin_Nav_Second extends Kostache {

	/**
	 * @var object  Request object
	 */
	public $request = NULL;

	/**
	 * @var array   Array of navigation items
	 */
	protected $items = NULL;

	/**
	 * @var string  Current group
	 */
	protected $group = NULL;

	/**
	 * Get the navigation heading
	 *
	 * @return  The navigation heading
	 */
	public function heading()
	{
		// If group is not yet determine, render items
		if ($this->group == NULL)
		{
			$this->items();
		}

		return __(':group Management', array(':group' => ucfirst($this->group)));
	}

	/**
	 * Get the navigation items to display
	 * as the secondary menu for the Admin App
	 *
	 * @return  Array of navigation links
	 */
	public function items()
	{
		// If items already rendered, return
		if ($this->items != NULL)
			return $this->items;

		// Create items array
		$this->items = array();

		// Get current request
		if ($this->request == NULL)
		{
			$this->request = Request::instance();
		}

		// Get current route
		$route = Route::name($this->request->route);

		if ($route == 'admin/external')
		{
			// Get current extension and route
			$extension = $this->request->param('extension');
			$this->group = Admin::group($extension);

			// Get registered extensions
			$groups = Admin::extensions();

			if (isset($groups[$this->group]))
			{
				foreach ($groups[$this->group] as $extension)
				{
					// Get the URL for the extension
					$url = Route::get('admin/external')->uri(array('extension' => $extension));

					// Add the extension to the navigation
					$this->items[__(ucfirst($extension))] = array('url' => $url);
				}
			}
		}

		return $this->items;
	}

}	// End of View_Admin_Nav_Second
