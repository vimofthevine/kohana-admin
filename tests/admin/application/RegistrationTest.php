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
	 * Test that extensions must be registered
	 */
	public function test_extensions_require_registration()
	{
	}

	/**
	 * Test that extensions register with a group
	 */
	public function test_extensions_register_with_group()
	{
	}

	/**
	 * Test that extensions can register as the first
	 * extension of a given group
	 */
	public function test_extension_can_register_as_first()
	{
	}

	/**
	 * Test third-level navigation registration with an
	 * extension group
	 */
	public function test_navigation_registration_with_group()
	{
	}

	/**
	 * Test third-level navigation registration with an
	 * extension-action pair
	 */
	public function test_navigation_registration_with_extension_action_pair()
	{
	}

	/**
	 * Test that widgets must be registered
	 */
	public function test_widgets_require_registration()
	{
	}

	/**
	 * Test that widgets register with identity and URL
	 */
	public function test_widget_registration_with_id_and_url()
	{
	}

}	// End of Admin_Application_RegistrationTest
