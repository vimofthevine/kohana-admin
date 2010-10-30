<?php defined('SYSPATH') or die('No direct script access.');

/**
 * Admin application core
 *
 * @package     Admin
 * @category    Base
 * @author      Kyle Treubig
 * @copyright   (C) 2010 Kyle Treubig
 * @license     MIT
 */
class Admin_Core {

	/** 200 Response Code */
	const RESPONSE_200 = 200;

	/** 301 Response Code */
	const RESPONSE_301 = 301;

	/** 400 Response Code */
	const RESPONSE_400 = 400;

	/** 403 Response Code */
	const RESPONSE_403 = 403;

	/** 404 Response Code */
	const RESPONSE_404 = 404;

	/**
	 * @var array   Registered extensions
	 */
	protected static $extensions = array();

	/**
	 * @var array   Registered navigation views
	 */
	protected static $navigations = array();

	/**
	 * @var array   Registered widgets
	 */
	protected static $widgets = array();

	/**
	 * Reset the Admin App
	 */
	public static function reset()
	{
		Kohana::$log->add(Kohana::DEBUG, 'Resetting Admin App');

		self::$extensions  = array();
		self::$navigations = array();
		self::$widgets     = array();
	}

	/**
	 * Is the given extension or widget is registered
	 * with the Admin App?
	 *
	 * @param   string  The extension or widget name
	 * @param   boolean Check against the registered widgets
	 * @return  True if the extension is registered, else false
	 */
	public static function registered($name, $widget = FALSE)
	{
		if ($widget)
			return array_key_exists($name, self::$widgets);
		else
			return in_array($name, array_values(Arr::flatten(self::$extensions)));
	}

	/**
	 * Register an extension with the Admin App
	 *
	 * @param   string  The name of the extension
	 * @param   string  The extension group
	 * @param   boolean Extension should be first in group
	 */
	public static function register($extension, $group = 'admin', $first = FALSE)
	{
		$blacklist = Kohana::config('admin.extensions.blacklist');

		// If the extension is blacklisted, do nothing
		if (in_array($extension, $blacklist))
		{
			Kohana::$log->add(Kohana::DEBUG, 'Extension :name is blacklisted, aborting registration',
				array(':name' => $extension));
			return;
		}

		// Create extension group array if one doesn't exist
		if ( ! isset(self::$extensions[$group]))
		{
			self::$extensions[$group] = array();
		}

		if ($first === TRUE)
		{
			// Place the extension at the top of the stack
			array_unshift(self::$extensions[$group], $extension);
		}
		else
		{
			// Place the extension at the bottom of the stack
			array_push(self::$extensions[$group], $extension);
		}
	}

	/**
	 * Get the array of registered extensions
	 *
	 * @return  The array of registered extensions
	 */
	public static function extensions()
	{
		return self::$extensions;
	}

	/**
	 * Get the group that a given extension is registered with
	 *
	 * @param   string  The extension name
	 * @return  The extension group
	 */
	public static function group($extension)
	{
		foreach (self::$extensions as $group => $extensions)
		{
			if (in_array($extension, $extensions))
				return $group;
		}
	}

	/**
	 * Register a navigation view
	 *
	 * @param   string  The navigation view class name
	 * @param   string  An extension group, extension,
	 *                  or extension-action pair
	 */
	public static function navigation($view, $match)
	{
		self::$navigations[$match] = $view;
	}

	/**
	 * Get an array of navigation views for a given
	 * extension-action pair
	 *
	 * @param   string  An extension-action pair
	 * @return  Array of matching navigation views
	 */
	public static function nav($pair)
	{
		$nav = array();

		// Split pair into extension and action
		if (strpos($pair, '/') != 0)
		{
			list($extension, $action) = explode('/', $pair);
		}
		else
		{
			$extension = $pair;
			$action    = '';
		}

		// Get the group the extension is registered to
		$group = self::group($extension);

		// Add navs that match the extension's group
		if (isset(self::$navigations[$group]))
		{
			$nav[] = self::$navigations[$group];
		}

		// Add navs that match the extension
		if (isset(self::$navigations[$extension]))
		{
			$nav[] = self::$navigations[$extension];
		}

		// Add navs that match the extension-action pair
		if (isset(self::$navigations[$extension.'/'.$action]))
		{
			$nav[] = self::$navigations[$extension.'/'.$action];
		}

		return $nav;
	}

	/**
	 * Register a widget with the Admin App
	 *
	 * @param   string  The name of the widget
	 * @param   string  The internal URL to the widget
	 */
	public static function widget($name, $url)
	{
		$blacklist = Kohana::config('admin.widgets.blacklist');

		// If the widget is blacklisted, do nothing
		if (in_array($name, $blacklist))
		{
			Kohana::$log->add(Kohana::DEBUG, 'Widget :name is blacklisted, aborting registration',
				array(':name' => $name));
			return;
		}

		Kohana::$log->add(Kohana::DEBUG, 'Registering widget :name',
			array(':name' => $name));

		// Add the widget to the registered widgets list
		self::$widgets[$name] = $url;
	}

	/**
	 * Get the array of registered widgets
	 *
	 * @return  The array of registered widgets
	 */
	public static function widgets()
	{
		return self::$widgets;
	}

}	// End of Admin_Core
