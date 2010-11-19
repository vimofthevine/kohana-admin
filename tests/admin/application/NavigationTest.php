<?php defined('SYSPATH') or die('No direct script access.');

/**
 * PHPUnit tests for the Admin App's navigation
 * registration and rendering. All tests are
 * written as one spec per test.
 *
 * @see         http://github.com/vimofthevine/kohana-admin/wiki/Software-Requirements-Specification
 *
 * @group       admin
 * @group       admin.application
 * @group       admin.application.navigation
 *
 * @package     Admin
 * @category    Tests
 * @author      Kyle Treubig
 * @copyright   (C) 2010 Kyle Treubig
 * @license     MIT
 */
class Admin_Application_NavigationTest extends Kohana_Unittest_TestCase {

	/**
	 * Reset the Admin App before performing all tests
	 */
	public function setUp()
	{
		$server['route'] = 'admin';
		$request = Request::instance($server['route']);
		Admin::reset();
	}

	/**
	 * Test that the top-level navigation
	 * displays a login link
	 */
	public function test_top_nav_displays_login_link()
	{
		$a2 = $this->getMock('A2');
		$a2->expects($this->once())
			->method('logged_in')
			->will($this->returnValue(FALSE));

		// Get the top-level navigation view
		$view = $this->getMockForAbstractClass('View_Admin_Layout_Core');
		$view->a2 = $a2;

		$nav = $view->primary_nav_items();
		$end = count($nav) - 1;
		$this->assertEquals(__('Login'), $nav[$end]['text']);
	}

	/**
	 * Test that the top-level navigation
	 * displays a logout link
	 */
	public function test_top_nav_displays_logout_link()
	{
		$a2 = $this->getMock('A2');
		$a2->expects($this->once())
			->method('logged_in')
			->will($this->returnValue(TRUE));

		// Get the top-level navigation view
		$view = $this->getMockForAbstractClass('View_Admin_Layout_Core');
		$view->a2 = $a2;

		$nav = $view->primary_nav_items();
		$end = count($nav) - 1;
		$this->assertEquals(__('Logout'), $nav[$end]['text']);
	}

	/**
	 * Test that the top-level navigation
	 * displays a dashboard link
	 */
	public function test_top_nav_displays_dashboard_link()
	{
		// Get the top-level navigation view
		$view = $this->getMockForAbstractClass('View_Admin_Layout_Core');

		$nav = $view->primary_nav_items();
		$this->assertEquals(__('Home'), $nav[0]['text']);
	}

	/**
	 * Test that the top-level navigation
	 * displays registered extension groups
	 */
	public function test_top_nav_displays_registered_groups()
	{
		// Register some extensions with the Admin App
		Admin::register('profiles', 'users');
		Admin::register('articles', 'blog');

		// Get the top-level navigation view
		$view = $this->getMockForAbstractClass('View_Admin_Layout_Core');
		$nav = $view->primary_nav_items();

		// Verify dashboard, login, and group links
		$this->assertEquals(4, count($nav));
	}

	/**
	 * Test that the top-level navigation
	 * displays registered group names
	 */
	public function test_top_nav_displays_group_names()
	{
		// Register some extensions with the Admin App
		Admin::register('profiles', 'users');
		Admin::register('articles', 'blog');

		// Get the top-level navigation view
		$view = $this->getMockForAbstractClass('View_Admin_Layout_Core');
		$nav = $view->primary_nav_items();

		// Verify extension names displayed
		$this->assertEquals(__('Users'), $nav[1]['text']);
		$this->assertEquals(__('Blog'), $nav[2]['text']);
	}

	/**
	 * Test that the top-level navigation displays
	 * the first registered extension as the link
	 */
	public function test_top_nav_displays_first_extension_as_link()
	{
		// Register some extensions with the Admin App
		Admin::register('categories', 'blog');
		Admin::register('articles',   'blog');

		// Get the top-level navigation view
		$view = $this->getMockForAbstractClass('View_Admin_Layout_Core');
		$nav = $view->primary_nav_items();

		// Get the URL for the categories extension
		$route = Route::get('admin/external')->uri(array('extension' => 'categories'));

		// Verify the URL for the blog category is the categories URL
		$this->assertEquals($route, $nav[1]['url']);
	}

	/**
	 * Provider for testing that the top-level navigation
	 * marks the current group as active
	 */
	public function provider_test_nav_displays_current_group_as_active()
	{
		return array(
			array('admin',            0),
			array('admin/categories', 1),
			array('admin/login',      2),
		);
	}

	/**
	 * Test that the top-level navigation displays
	 * the current extension group as active
	 *
	 * @dataProvider provider_test_nav_displays_current_group_as_active
	 */
	public function test_top_nav_displays_current_group_as_active($uri, $index)
	{
		// Setup request URI
		Request::$instance = Request::factory($uri);

		// Register some extensions with the Admin App
		Admin::register('categories', 'blog');

		// Get the top-level navigation view
		$view = $this->getMockForAbstractClass('View_Admin_Layout_Core');
		$nav = $view->primary_nav_items();

		// Verify the URL for the blog category is the categories URL
		$this->assertTrue($nav[$index]['active']);
	}

	/**
	 * Test that a second-level navigation is displayed
	 * for more than one extension in a group
	 */
	public function test_second_nav_displayed_for_multiple_extensions()
	{
		// Setup request URI
		Request::$instance = Request::factory('admin/categories');

		// Register some extensions with the Admin App
		Admin::register('categories', 'blog');
		Admin::register('articles',   'blog');

		// Get the second-level navigation view
		$view = $this->getMockForAbstractClass('View_Admin_Layout_Core');

		// Verify related extensions will be displayed
		$this->assertTrue($view->has_group_extensions());
	}

	/**
	 * Test that the second-level navigation displays
	 * the registered extension names as labels
	 */
	public function test_second_nav_displays_registered_extension_names()
	{
		// Setup request URI
		Request::$instance = Request::factory('admin/admins');

		// Register some extensions with the Admin App
		Admin::register('admins', 'users');
		Admin::register('mods',   'users');

		// Get the second-level navigation view
		$view = $this->getMockForAbstractClass('View_Admin_Layout_Core');
		$nav = $view->group_extensions();

		// Verify extension names displayed
		$this->assertEquals(__('Admins'), $nav[0]['text']);
		$this->assertEquals(__('Mods'), $nav[1]['text']);
	}

	/**
	 * Test that the second-level navigation displays
	 * the registered extension index actions as targets
	 */
	public function test_second_nav_displays_extension_index_actions()
	{
		// Setup request URI
		Request::$instance = Request::factory('admin/categories');

		// Register some extensions with the Admin App
		Admin::register('categories', 'blog');
		Admin::register('articles',   'blog');

		// Get the second-level navigation view
		$view = $this->getMockForAbstractClass('View_Admin_Layout_Core');
		$nav = $view->group_extensions();

		// Verify the URL for the extensions are used
		$route = Route::get('admin/external')->uri(array('extension' => 'categories'));
		$this->assertEquals($route, $nav[0]['url']);

		$route = Route::get('admin/external')->uri(array('extension' => 'articles'));
		$this->assertEquals($route, $nav[1]['url']);
	}

	/**
	 * Test that the second-level navigation displays
	 * the group as the navigation heading
	 */
	public function test_second_nav_displays_group_as_heading()
	{
		// Setup request URI
		Request::$instance = Request::factory('admin/categories');

		// Register some extensions with the Admin App
		Admin::register('categories', 'blog');
		Admin::register('articles',   'blog');

		// Get the second-level navigation view
		$view = $this->getMockForAbstractClass('View_Admin_Layout_Core');

		// Verify heading
		$this->assertEquals('Blog Management', $view->group_mgmt_heading());
	}

}	// End of Admin_Application_NavigationTest
