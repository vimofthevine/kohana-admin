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
	 * Reset the Admin App before performing all tests
	 */
	public function setUp()
	{
		Admin::reset();

		// Reset ordering config
		$config = Kohana::config('admin');
		$config['widgets']['order'] = array();
		$config->set('admin', $config->as_array());
	}

	/**
	 * Test that the dashboard displays registered
	 * widgets in a default number of columns if
	 * no ordering configuration is defined
	 */
	public function test_dashboard_displays_default_num_of_columns()
	{
		// Get the dashboard view
		$view = new View_Admin_Dashboard;

		// Verify default number of columns generated
		$this->assertEquals(3, count($view->columns()));
	}

	/**
	 * Test that the dashboard displays registered
	 * widgets in order of registration if
	 * no ordering configuration is defined
	 */
	public function test_dashboard_displays_widgets_in_default_order()
	{
		// Register widgets with the Admin App
		Admin::widget('widget1', 'url/to/widget/1');
		Admin::widget('widget2', 'url/to/widget/2');
		Admin::widget('widget3', 'url/to/widget/3');
		Admin::widget('widget4', 'url/to/widget/4');

		// Get the dashboard view
		$view = new View_Admin_Dashboard;

		// Get the widgets (debug mode)
		$widgets = $view->widgets(TRUE);

		// Verify default order of columns
		$this->assertEquals('url/to/widget/1', $widgets[0][0]); // first column, first row
		$this->assertEquals('url/to/widget/2', $widgets[1][0]); // second column, first row
		$this->assertEquals('url/to/widget/3', $widgets[2][0]); // third column, first row
		$this->assertEquals('url/to/widget/4', $widgets[0][1]); // first column, second row
	}

	/**
	 * Test that the dashboard displays registered
	 * widgets in the number of columns given
	 * in the defined ordering configuration
	 */
	public function test_dashboard_displays_defined_num_of_columns()
	{
		// Add ordering configuration
		$config = Kohana::config('admin');
		$config['widgets']['order'] = array(
			array(),
			array(),
		);
		$config->set('admin', $config->as_array());

		// Get the dashboard view
		$view = new View_Admin_Dashboard;

		// Verify defined number of columns generated
		$this->assertEquals(2, count($view->columns()));
	}

	/**
	 * Test that the dashboard displays registered
	 * widgets in the order given in the
	 * defined ordering configuration
	 */
	public function test_dashboard_displays_widgets_in_defined_order()
	{
		// Add ordering configuration
		$config = Kohana::config('admin');
		$config['widgets']['order'] = array(
			array('widget3', 'widget4'),
			array('widget1', 'widget2'),
		);
		$config->set('admin', $config->as_array());

		// Register widgets with the Admin App
		Admin::widget('widget1', 'url/to/widget/1');
		Admin::widget('widget2', 'url/to/widget/2');
		Admin::widget('widget3', 'url/to/widget/3');
		Admin::widget('widget4', 'url/to/widget/4');

		// Get the dashboard view
		$view = new View_Admin_Dashboard;

		// Get the widgets (debug mode)
		$widgets = $view->widgets(TRUE);

		// Verify default order of columns
		$this->assertEquals('url/to/widget/3', $widgets[0][0]); // first column, first row
		$this->assertEquals('url/to/widget/1', $widgets[1][0]); // second column, first row
		$this->assertEquals('url/to/widget/4', $widgets[0][1]); // first column, second row
		$this->assertEquals('url/to/widget/2', $widgets[1][1]); // second column, second row
	}

	/**
	 * Test that the dashboard does not display a widget that
	 * is not registered
	 */
	public function test_dashboard_does_not_display_unregistered_widget()
	{
		// Add ordering configuration
		$config = Kohana::config('admin');
		$config['widgets']['order'] = array(
			array('not-registered-widget'),
		);
		$config->set('admin', $config->as_array());

		// Get the dashboard view
		$view = new View_Admin_Dashboard;

		// Get the widgets (debug mode)
		$widgets = $view->widgets(TRUE);

		// Verify no widget displayed
		$this->assertEquals(0, count(Arr::flatten($widgets)));
	}

	/**
	 * Test that the dashboard executes an internal
	 * request to render each widget
	 */
	public function test_dashboard_executes_widget_request()
	{
		// Register widgets with the Admin App
		Admin::widget('widget1', 'admin/extension/unittest/widget_200');

		// Get the dashboard view
		$view = new View_Admin_Dashboard;

		// Get the widgets (debug mode)
		$widgets = $view->widgets();

		// Verify widget results displayed
		$this->assertEquals(1, count(Arr::flatten($widgets)));
	}

	/**
	 * Provider for testing that widgets with errors
	 * are not displayed
	 */
	public function provider_dashboard_does_not_display_widgets_with_errors()
	{
		return array(
			array('widget1', 'admin/extension/unittest/widget_301'),
			array('widget2', 'admin/extension/unittest/widget_403'),
			array('widget3', 'admin/extension/unittest/widget_404'),
			array('widget4', 'admin/extension/unittest/widget_exception'),
			array('widget5', 'admin/extension/unittest/widget_dne'),
			array('widget6', 'admin/extension/ext/dne'),
		);
	}

	/**
	 * Test that the dashboard does not display
	 * widgets with 301, 403, or 404 status results,
	 * or throws an exception
	 *
	 * @dataProvider provider_dashboard_does_not_display_widgets_with_errors
	 */
	public function test_dashboard_does_not_displays_widgets_with_errors($widget, $url)
	{
		// Register widget with the Admin App
		Admin::widget($widget, $url);

		// Get the dashboard view
		$view = new View_Admin_Dashboard;

		// Get the widgets
		$widgets = $view->widgets();

		// Verify no widget displayed
		$this->assertEquals(0, count(Arr::flatten($widgets)));
	}

	/**
	 * Test that the dashboard displays the
	 * user message of a widet
	 */
	public function test_dashboard_displays_user_message()
	{
		// Register widget with the Admin App
		Admin::widget('widget5', 'admin/extension/unittest/widget_message');

		// Get the dashboard view
		$view = new View_Admin_Dashboard;

		// Get the widgets
		$widgets = $view->widgets();

		// Verify user message displayed
		$this->assertEquals('USER MESSAGE', $widgets[0][0]);
	}

	/**
	 * Test that the dashboard displays the
	 * widget response
	 */
	public function test_dashboard_displays_widget_response()
	{
		// Register widget with the Admin App
		Admin::widget('widget6', 'admin/extension/unittest/widget_400');

		// Get the dashboard view
		$view = new View_Admin_Dashboard;

		// Get the widgets
		$widgets = $view->widgets();

		// Verify user message displayed
		$this->assertEquals('WIDGET 400', $widgets[0][0]);
	}

}	// End of Admin_Application_DashboardTest
