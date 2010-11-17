<?php defined('SYSPATH') or die('No direct script access.');

/**
 * PHPUnit tests for the core layout view,
 * testing elements not covered by the
 * design requirements. All tests are written
 * as one spec per test.
 *
 * @group       admin
 * @group       admin.view
 * @group       admin.view.layout
 *
 * @package     Admin
 * @category    Tests
 * @author      Kyle Treubig
 * @copyright   (C) 2010 Kyle Treubig
 * @license     MIT
 */
class Admin_View_LayoutTest extends Kohana_Unittest_TestCase {

	/**
	 * Create an instance of the core layout view
	 */
	public function setUp()
	{
		// Reset the admin app
		Admin::reset();

		// Create the view
		$this->view = $this->getMockForAbstractClass('View_Admin_Layout_Core');
	}

	/**
	 * Test the stylesheets method of the view
	 */
	public function test_stylesheets()
	{
		// Add test stylesheet to view
		$this->view->_stylesheets += array('test/file.css' => 'testmedia');

		// Verify stylesheets
		$this->assertRegExp('/test\/file\.css.*testmedia/', $this->view->stylesheets());
	}

	/**
	 * Test the scripts method of the view
	 */
	public function test_scripts()
	{
		// Add test script to view
		$this->view->_scripts += array('test/file.js');

		// Verify scripts
		$this->assertRegExp('/test\/file\.js/', $this->view->scripts());
	}

	/**
	 * Test that nothing returned for info
	 * message if no messages in session
	 */
	public function test_no_info_message()
	{
		// Verify no message in view
		$this->assertNull($this->view->info_message());
	}

	/**
	 * Test the info message of the view
	 */
	public function test_info_message()
	{
		// Add a test info message
		Admin::message('test_info_msg', Admin::INFO);

		// Verify message in view
		$this->assertRegExp('/test_info_msg/', $this->view->info_message());
	}

	/**
	 * Test that nothing returned for error
	 * message if no messages in session
	 */
	public function test_no_error_message()
	{
		// Verify no message in view
		$this->assertNull($this->view->error_message());
	}

	/**
	 * Test the error message of the view
	 */
	public function test_error_message()
	{
		// Add a test error message
		Admin::message('test_error_msg', Admin::ERROR);

		// Verify message in view
		$this->assertRegExp('/test_error_msg/', $this->view->error_message());
	}

	/**
	 * Provider for the testing of the
	 * group method of the core layout view
	 */
	public function provider_test_group()
	{
		return array(
			array('admin',            'admin'),
			array('admin/categories', 'blog'),
			array('admin/login',      'admin/auth'),
		);
	}

	/**
	 * Test the group method of the view
	 *
	 * @dataProvider provider_test_group
	 */
	public function test_group($uri, $group)
	{
		// Setup request URI
		Request::$instance = Request::factory($uri);

		// Register some extensions with the Admin App
		Admin::register('categories', 'blog');

		// Verify group
		$this->assertEquals($group, $this->view->group());
	}

	/**
	 * Provider for the testing of the
	 * extension method of the core layout view
	 */
	public function provider_test_extension()
	{
		return array(
			array('admin',            ''),
			array('admin/categories', 'categories'),
			array('admin/login',      ''),
		);
	}

	/**
	 * Test the extension method of the view
	 *
	 * @dataProvider provider_test_extension
	 */
	public function test_extension($uri, $extension)
	{
		// Setup request URI
		Request::$instance = Request::factory($uri);

		// Register some extensions with the Admin App
		Admin::register('categories', 'blog');

		// Verify extension
		$this->assertEquals($extension, $this->view->extension());
	}

	/**
	 * Provider for the testing of the
	 * action method of the core layout view
	 */
	public function provider_test_action()
	{
		return array(
			array('admin',                 'index'),
			array('admin/categories/list', 'list'),
			array('admin/login',           'login'),
		);
	}

	/**
	 * Test the action method of the view
	 *
	 * @dataProvider provider_test_action
	 */
	public function test_action($uri, $action)
	{
		// Setup request URI
		Request::$instance = Request::factory($uri);

		// Register some extensions with the Admin App
		Admin::register('categories', 'blog');

		// Verify action
		$this->assertEquals($action, $this->view->action());
	}

	/**
	 * Provider for testing the registered
	 * navs method of the core layout view
	 */
	public function provider_test_registered_navs()
	{
		return array(
			// no match
			array('admin/nonav',           ''),
			// matches group
			array('admin/articles',        'BLOG GROUP NAV'),
			// matches group, extension
			array('admin/categories',      'BLOG GROUP NAV'),
			array('admin/categories',      'CATEGORIES EXTENSION NAV'),
			// matches group, extension, action
			array('admin/categories/edit', 'BLOG GROUP NAV'),
			array('admin/categories/edit', 'CATEGORIES EXTENSION NAV'),
			array('admin/categories/edit', 'EDIT ACTION NAV'),
		);
	}

	/**
	 * Test the registered navigations of the view
	 *
	 * @dataProvider provider_test_registered_navs
	 */
	public function test_registered_navs($uri, $search)
	{
		// Setup request URI
		Request::$instance = Request::factory($uri);

		// Register navigation views
		Admin::navigation('admin/test/nav/group',  'blog');
		Admin::navigation('admin/test/nav/ext',    'categories');
		Admin::navigation('admin/test/nav/action', 'categories/edit');

		// Register some extensions with the Admin App
		Admin::register('categories', 'blog');
		Admin::register('articles', 'blog');

		// Verify navs
		$this->assertRegExp('/'.$search.'/', $this->view->registered_navs());
	}

	/**
	 * Provider for testing that the layout view
	 * injects into a grid layout
	 */
	public function provider_test_view_injected_into_grid()
	{
		return array(
			array('View_Admin_Layout_Full', 'grid_16'),
			array('View_Admin_Layout_Wide', 'grid_12'),
			array('View_Admin_Layout_Narrow', 'grid_8'),
		);
	}

	/**
	 * Assert that the layout views inject the
	 * view into the specified grid layout
	 *
	 * @dataProvider provider_test_view_injected_into_grid
	 */
	public function test_view_injected_into_grid($class, $search)
	{
		$view = $this->getMockForAbstractClass($class);
		$this->assertRegExp('/'.$search.'/', $view->render());
	}

	/**
	 * Assert that the layout view does not inject into
	 * the admin template if use_layout is false
	 */
	public function test_view_not_injected()
	{
		$view = $this->getMockForAbstractClass('View_Admin_Layout_Full');
		$view->set('_template', 'admin/layout/narrow');
		$view->use_layout = FALSE;
		$this->assertNotRegExp('/<html/', $view->render());
	}

}	// End of Admin_View_LayoutTest
