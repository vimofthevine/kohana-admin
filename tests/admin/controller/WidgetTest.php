<?php defined('SYSPATH') or die('No direct script access.');

/**
 * PHPUnit tests for the Admin App's widget
 * controller. All tests are written as one
 * spec per test.
 *
 * @see         http://github.com/vimofthevine/kohana-admin/wiki/Software-Requirements-Specification
 *
 * @group       admin
 * @group       admin.controller
 * @group       admin.controller.widget
 *
 * @package     Admin
 * @category    Tests
 * @author      Kyle Treubig
 * @copyright   (C) 2010 Kyle Treubig
 * @license     MIT
 */
class Admin_Controller_WidgetTest extends Kohana_Unittest_TestCase {

	/**
	 * Test that the widget controller redirects to 404 error
	 * if the current request is not an internal request
	 */
	public function test_widget_controller_redirects_404_if_not_internal()
	{
	}

	/**
	 * Test that the widget controller sets request
	 * status to 404 if requested extension is not
	 * registered
	 */
	public function test_widget_controller_sets_404_if_ext_not_registered()
	{
	}

	/**
	 * Test that the widget controller creates
	 * instance of requested extension if it exists
	 */
	public function test_widget_controller_creates_requested_extension()
	{
	}

	/**
	 * Test that the widget controller creates
	 * instance of default extension if requested
	 * extension does not exist and config group exists
	 */
	public function test_widget_controller_creates_default_extension()
	{
	}

	/**
	 * Test that the widget controller sets request
	 * status to 404 if the requested extension does
	 * not exist and config group does not exist
	 */
	public function test_widget_controller_sets_404_if_ext_dne()
	{
	}

	/**
	 * Test that the widget controller sets request
	 * status to 404 if the requested action does not exist
	 */
	public function test_widget_controller_sets_404_if_action_dne()
	{
	}

	/**
	 * Test that the widget controller executes the requested action
	 */
	public function test_widget_controller_executes_action()
	{
	}

	/**
	 * Test that the widget controller sets the request
	 * status to the widget's action result status
	 */
	public function test_widget_controller_sets_status_to_widget_result_status()
	{
	}

	/**
	 * Test that the widget controller sets the request
	 * response to the widget's action result message
	 * if a user message is given and result is 200
	 */
	public function test_widget_controller_sets_response_to_message_if_given_and_200()
	{
	}

	/**
	 * Test that the widget controller sets the request
	 * response to the widget's action result message
	 * if a user message is given and result is 400
	 */
	public function test_widget_controller_sets_response_to_message_if_given_and_400()
	{
	}

	/**
	 * Test that the widget controller sets the request
	 * response to the widget's action result resopnse
	 * if the result status is 200 and no message given
	 */
	public function test_widget_controller_sets_response_if_no_message_and_200()
	{
	}

	/**
	 * Test that the widget controller sets the request
	 * response to the widget's action result resopnse
	 * if the result status is 400 and no message given
	 */
	public function test_widget_controller_sets_response_if_no_message_and_400()
	{
	}

}	// End of Admin_Controller_WidgetTest
