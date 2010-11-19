<?php defined('SYSPATH') or die('No direct script access.');

/**
 * Stub view class for PHPUnit testing
 *
 * @package     Admin
 * @category    Tests
 * @author      Kyle Treubig
 * @copyright   (C) 2010 Kyle Treubig
 * @license     MIT
 */
class View_Admin_Test_Nav_Group extends Kostache {

	public function render($template = NULL, $view = NULL, $partials = NULL)
	{
		return 'BLOG GROUP NAV';
	}

}
