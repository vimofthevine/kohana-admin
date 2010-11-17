<?php defined('SYSPATH') or die('No direct script access.');

/**
 * PHPUnit tests for the Admin App's extension,
 * navigation, and widget registrations. All tests
 * are written as one spec per test.
 *
 * @see         http://github.com/vimofthevine/kohana-admin/wiki/Software-Requirements-Specification
 *
 * @group       admin
 * @group       admin.application
 * @group       admin.application.registration
 *
 * @package     Admin
 * @category    Tests
 * @author      Kyle Treubig
 * @copyright   (C) 2010 Kyle Treubig
 * @license     MIT
 */
class Admin_Application_RegistrationTest extends Kohana_Unittest_TestCase {

	/**
	 * Reset the Admin App before performing all tests
	 */
	public function setUp()
	{
		Admin::reset();
	}

	/**
	 * Test that extensions must be registered
	 */
	public function test_extensions_require_registration()
	{
		// Test with non-registered extension
		$this->assertFalse(Admin::registered('non-registered-ext'));

		// Test with registered extension
		Admin::register('registered-ext');
		$this->assertTrue(Admin::registered('registered-ext'));
	}

	/**
	 * Test that extensions register with a group
	 */
	public function test_extensions_register_with_group()
	{
		// Register extensions with groups 'blog' and 'users'
		Admin::register('categories', 'blog');
		Admin::register('articles', 'blog');
		Admin::register('profiles', 'users');

		// Get registered extensions
		$extensions = Admin::extensions();

		// Verify number of groups returned
		$this->assertEquals(2, count($extensions));

		// Verify 'blog' and 'users' groups returned
		$this->assertArrayHasKey('blog',  $extensions);
		$this->assertArrayHasKey('users', $extensions);

		// Verify number of extensions in each group
		$this->assertEquals(2, count($extensions['blog']));
		$this->assertEquals(1, count($extensions['users']));

		// Verify extensions under each group
		$this->assertContains('profiles',   $extensions['users']);
		$this->assertContains('categories', $extensions['blog']);
		$this->assertContains('articles',   $extensions['blog']);

		// Verify group function returns proper group
		$this->assertEquals('blog',  Admin::group('articles'));
		$this->assertEquals('users', Admin::group('profiles'));
	}

	/**
	 * Test that extensions can register as the first
	 * extension of a given group
	 */
	public function test_extension_can_register_as_first()
	{
		// Register extensions with group 'blog'
		Admin::register('categories', 'blog');
		Admin::register('tags', 'blog');
		Admin::register('articles', 'blog', TRUE);

		// Get registered extensions
		$extensions = Admin::extensions();

		// Verify number of extensions in group
		$this->assertEquals(3, count($extensions['blog']));

		// Verify order of extensions
		$this->assertEquals('articles',   $extensions['blog'][0]);
		$this->assertEquals('categories', $extensions['blog'][1]);
		$this->assertEquals('tags',       $extensions['blog'][2]);
	}

	/**
	 * Test that blacklisted extensions are not registered
	 */
	public function test_blacklisted_extensions_are_not_registered()
	{
		// Add extension to blacklist
		$config = Kohana::config('admin');
		$config['extensions']['blacklist'] = array('blog');
		$config->set('admin', $config->as_array());

		// Attempt to register the extension
		Admin::register('blog');

		// Verify extension was not registered
		$this->assertFalse(Admin::registered('blog'));
	}

	/**
	 * Test third-level navigation registration with an
	 * extension group
	 */
	public function test_navigation_registration_with_group()
	{
		// Register navigation with group 'blog'
		Admin::navigation('Blog_Navigation_Class', 'blog');

		// Register extension with group
		Admin::register('articles', 'blog');

		// Verify that registered navigation matches given group
		$this->assertContains('Blog_Navigation_Class', Admin::nav('articles'));
	}

	/**
	 * Test third-level navigation registration with an extension
	 */
	public function test_navigation_registration_with_extension()
	{
		// Register navigation with extension articles
		Admin::navigation('Articles_Navigation_Class', 'articles');

		// Verify that registered navigation matches given extension
		$this->assertContains('Articles_Navigation_Class', Admin::nav('articles'));
	}

	/**
	 * Test third-level navigation registration with an
	 * extension-action pair
	 */
	public function test_navigation_registration_with_extension_action_pair()
	{
		// Register navigation with extension-action pair
		Admin::navigation('Edit_Navigation_Class', 'articles/edit');

		// Verify that registered navigation matches given ext-action pair
		$this->assertContains('Edit_Navigation_Class', Admin::nav('articles', 'edit'));
	}

	/**
	 * Test that widgets must be registered
	 */
	public function test_widgets_require_registration()
	{
		// Test with non-registered widget
		$this->assertFalse(Admin::registered('non-registered-widget', TRUE));

		// Test with registered widget
		Admin::widget('registered-widget', 'url');
		$this->assertTrue(Admin::registered('registered-widget', TRUE));
	}

	/**
	 * Test that widgets register with identity and URL
	 */
	public function test_widget_registration_with_id_and_url()
	{
		// Register widgets
		Admin::widget('widget1', 'url/to/widget/1');
		Admin::widget('widget2', 'url/to/widget/2');

		// Get registered widgets
		$widgets = Admin::widgets(FALSE);

		// Verify number of widgets returned
		$this->assertEquals(2, count($widgets));

		// Verify widget identites
		$this->assertArrayHasKey('widget1', $widgets);
		$this->assertArrayHasKey('widget2', $widgets);

		// Verify widget URLs
		$this->assertEquals('url/to/widget/1', $widgets['widget1']);
		$this->assertEquals('url/to/widget/2', $widgets['widget2']);
	}

	/**
	 * Test that blacklisted widgets are not registered
	 */
	public function test_blacklisted_widgets_are_not_registered()
	{
		// Add widgets to blacklist
		$config = Kohana::config('admin');
		$config['widgets']['blacklist'] = array('blocked-widget');
		$config->set('admin', $config->as_array());

		// Attempt to register the widget
		Admin::widget('blocked-widget', 'url/to/widget');

		// Verify widget was not registered
		$this->assertFalse(Admin::registered('blocked-widget', TRUE));
	}

}	// End of Admin_Application_RegistrationTest
