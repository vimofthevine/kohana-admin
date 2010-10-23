<?php defined('SYSPATH') or die('No direct script access.');

/**
 * PHPUnit tests for the Admin App's dashboard.
 * All tests are written as one spec per test.
 *
 * @see         http://github.com/vimofthevine/kohana-admin/wiki/Software-Requirements-Specification
 *
 * @group       admin
 * @group       admin.application
 * @group       admin.application.dashboard
 *
 * @package     Admin
 * @category    Tests
 * @author      Kyle Treubig
 * @copyright   (C) 2010 Kyle Treubig
 * @license     MIT
 */
class Admin_Application_DashboardTest extends Kohana_Unittest_TestCase {

	/**
	 * Test that the dashboard displays registered
	 * widgets in a default number of columns if
	 * no ordering configuration is defined
	 */
	public function test_dashboard_displays_default_num_of_columns()
	{
	}

	/**
	 * Test that the dashboard displays registered
	 * widgets in order of registration if
	 * no ordering configuration is defined
	 */
	public function test_dashboard_displays_widgets_in_default_order()
	{
	}

	/**
	 * Test that the dashboard displays registered
	 * widgets in the number of columns given
	 * in the defined ordering configuration
	 */
	public function test_dashboard_displays_defined_num_of_columns()
	{
	}

	/**
	 * Test that the dashboard displays registered
	 * widgets in the order given in the
	 * defined ordering configuration
	 */
	public function test_dashboard_displays_widgets_in_defined_order()
	{
	}

	/**
	 * Test that the dashboard does not display
	 * widgets with 301, 403, or 404 status results
	 */
	public function test_dashboard_does_not_displays_widgets_with_errors()
	{
	}

}	// End of Admin_Application_DashboardTest
