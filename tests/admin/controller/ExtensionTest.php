<?php defined('SYSPATH') or die('No direct script access.');

/**
 * PHPUnit tests for the Admin App's extension
 * controller. All tests are written as
 * one spec per test.
 *
 * @see         http://github.com/vimofthevine/kohana-admin/wiki/Software-Requirements-Specification
 *
 * @group       admin
 * @group       admin.controller
 * @group       admin.controller.extension
 *
 * @package     Admin
 * @category    Tests
 * @author      Kyle Treubig
 * @copyright   (C) 2010 Kyle Treubig
 * @license     MIT
 */
class Admin_Controller_ExtensionTest extends Kohana_Unittest_TestCase {

	/**
	 * Test that the extension controller redirects to 404 error
	 * if requested extension is not registered
	 */
	public function test_ext_controller_redirects_404_if_ext_not_registered()
	{
	}

	/**
	 * Test that the extension controller creates
	 * instance of requested extension if it exists
	 */
	public function test_ext_controller_creates_requested_extension()
	{
	}

	/**
	 * Test that the extension controller creates
	 * instance of default extension if requested
	 * extension does not exist and config group exists
	 */
	public function test_ext_controller_creates_default_extension()
	{
	}

	/**
	 * Test that the extension controller redirects to 404 error
	 * if requested extension does not exist and config group
	 * does not exist
	 */
	public function test_ext_controller_redirects_404_if_ext_dne()
	{
	}

	/**
	 * Test that the extension controller redirects to 404 error
	 * if the requested action does not exist
	 */
	public function test_ext_controller_redirects_404_if_action_dne()
	{
	}

	/**
	 * Test that the extension controller executes requested action
	 */
	public function test_ext_controller_executes_action()
	{
	}

	/**
	 * Test that the extension controller redirects to login
	 * if extension result status is 403 and not logged in
	 */
	public function test_ext_controller_redirects_login_if_403_and_not_logged_in()
	{
	}

	/**
	 * Test that the extension controller redirects to previous page
	 * if extension result status is 403 and logged in
	 */
	public function test_ext_controller_redirects_previous_if_403_and_logged_in()
	{
	}

	/**
	 * Test that the extension controller sets flash message
	 * if user message set and result status is 403
	 */
	public function test_ext_controller_sets_flash_if_message_given_and_403()
	{
	}

	/**
	 * Test that the extension controller redirects to given URL
	 * if extension result status is 404
	 */
	public function test_ext_controller_redirects_url_if_404()
	{
	}

	/**
	 * Test that the extension controller sets flash message
	 * if user message set and result status is 404
	 */
	public function test_ext_controller_sets_flash_if_message_given_and_404()
	{
	}

	/**
	 * Test that the extension controller redirects to given URL
	 * if URL is given and extension result status is 200
	 */
	public function test_ext_controller_redirects_url_if_given_and_200()
	{
	}

	/**
	 * Test that the extension controller redirects to given URL
	 * if URL is given and extension result status is 400
	 */
	public function test_ext_controller_redirects_url_if_given_and_400()
	{
	}

	/**
	 * Test that the extension controller sets flash message
	 * if user message set and result status is 200
	 */
	public function test_ext_controller_sets_flash_if_message_given_and_200()
	{
	}

	/**
	 * Test that the extension controller sets flash message
	 * if user message set and result status is 400
	 */
	public function test_ext_controller_sets_flash_if_message_given_and_400()
	{
	}

	/**
	 * Test that the extension controller sets HTML response
	 * if no redirect URL given and result status is 200
	 */
	public function test_ext_controller_sets_response_if_no_url_and_200()
	{
	}

	/**
	 * Test that the extension controller sets HTML response
	 * if no redirect URL given and result status is 400
	 */
	public function test_ext_controller_sets_response_if_no_url_and_400()
	{
	}

	/**
	 * Test that the extension controller injects the
	 * extension response into the given layout if
	 * no redirect URL is given and result status is 200
	 */
	public function test_ext_controller_injects_into_layout_if_no_url_and_200()
	{
	}

	/**
	 * Test that the extension controller injects the
	 * extension response into the given layout if
	 * no redirect URL is given and result status is 400
	 */
	public function test_ext_controller_injects_into_layout_if_no_url_and_400()
	{
	}

}	// End of Admin_Controller_ExtensionTest
