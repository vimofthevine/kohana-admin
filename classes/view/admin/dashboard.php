<?php defined('SYSPATH') or die('No direct script access.');

/**
 * Dashboard view
 *
 * @package     Admin
 * @category    Views
 * @author      Kyle Treubig
 * @copyright   (C) 2010 Kyle Treubig
 * @license     MIT
 */
class View_Admin_Dashboard extends Kostache {

	/**
	 * @var Grid column widths
	 */
	protected $grid = array(
		1 => array('grid_16'),
		2 => array('grid_8', 'grid_8'),
		3 => array('grid_5', 'grid_6', 'grid_5'),
		4 => array('grid_4', 'grid_4', 'grid_4', 'grid_4'),
		5 => array('grid_3', 'grid_3', 'grid_4', 'grid_3', 'grid_3'),
	);

	/**
	 * @var array   The widget order as configured
	 */
	protected $config = NULL;

	/**
	 * @var array   The registered widgets
	 */
	protected $widgets = NULL;

	/**
	 * Get the ordering configuration
	 *
	 * @return  The configured widget order
	 */
	protected function get_config()
	{
		if ($this->config == NULL)
		{
			$this->config = Kohana::config('admin.widgets.order');
		}

		return $this->config;
	}

	/**
	 * Get the registered widgets
	 *
	 * @return  Array of registered widgets
	 */
	protected function get_widgets()
	{
		if ($this->widgets == NULL)
		{
			$this->widgets = Admin::widgets();
		}

		return $this->widgets;
	}

	/**
	 * Get the widget result
	 *
	 * @param   string  The widget name
	 * @param   boolean Return URLs rather than
	 *                  widget results (debug mode)
	 * @return  The widget response, or NULL if not valid
	 */
	protected function get_widget($name, $debug = FALSE)
	{
		// Get registered widgets
		$this->get_widgets();

		// If no registered widgets, do nothing
		if ( ! isset($this->widgets[$name]))
		{
			Kohana::$log->add(Kohana::ERROR, 'Requested widget, :name, is not registered',
				array(':name' => $name));
			return NULL;
		}

		// If in debug mode, return widget URL
		if ($debug)
			return $this->widgets[$name];

		// Execute the widget
		try
		{
			$request = Request::factory($this->widgets[$name]);
			$request->execute();
		}
		catch (Exception $e)
		{
			Kohana::$log->add(Kohana::ERROR, 'Error exception occured executing :name widget, :exception',
				array(':name' => $name, ':exception' => $e->getMessage()));
			return NULL;
		}

		// If error occured, return nothing
		if ($request->status == Admin::RESPONSE_301 OR
			$request->status == Admin::RESPONSE_403 OR
			$request->status == Admin::RESPONSE_404)
			return NULL;

		if (isset($request->message) AND ! empty($request->message))
			return $request->message;

		// Return the result of the widget
		return $request->response;
	}

	/**
	 * Get the column widths for the dashboard
	 *
	 * @return  Array of column widths
	 */
	public function columns()
	{
		// Get configured widget order
		$config = $this->get_config();

		// If order not defined
		if (empty($config) OR count($config) == 0)
		{
			// Default to 3-column grid
			$num = 3;
		}
		else
		{
			// Get number of columns
			$num = count($config);
		}

		// Return the array of column widths
		return $this->grid[$num];
	}

	/**
	 * Get the dashboard widgets
	 *
	 * @param   boolean Execute in debug mode, returning the
	 *                  widget URLs rather than executing the
	 *                  internal requests (unit testing purposes)
	 * @return  Array of widgets
	 */
	public function widgets($debug = FALSE)
	{
		// Get configured widget order
		$config = $this->get_config();

		// Get registered widgets
		$this->get_widgets();

		// Prepare widgets array
		$widgets = array();

		// If order not defined
		if (empty($config) OR count($config) == 0)
		{
			// Start column counter at 0
			$i = 0;

			// For all registered widgets
			foreach ($this->widgets as $id => $url)
			{
				// Get widget result
				$widget = $this->get_widget($id, $debug);

				// If widget valid
				if ($widget !== NULL)
				{
					// Add to widgets array
					$widgets[$i][] = $widget;
				}

				// Increment column counter
				$i = ($i + 1) % 3;
			}
		}
		// Else use configured order
		else
		{
			// For each column
			for ($i = 0; $i < count($config); $i++)
			{
				// for each row
				for ($j = 0; $j < count($config[$i]); $j++)
				{
					// Get widget result
					$widget = $this->get_widget($config[$i][$j], $debug);

					// If widget valid
					if ($widget !== NULL)
					{
						// Add to widgets array
						$widgets[$i][] = $widget;
					}
				}
			}
		}

		return $widgets;
	}

}	// End of View_Admin_Dashboard
