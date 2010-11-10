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
	 * Reset the Admin App before performing all tests
	 */
	public function setUp()
	{
		// Reset request route
		$server['route'] = 'admin';
		$request = Request::instance($server['route']);

		Admin::reset();

		// Reset messages
		Admin::messages();
	}

	/**
	 * Test that the extension controller redirects
	 * to 404 error if requested extension is not registered
	 */
	public function test_ext_controller_redirects_404_if_ext_not_registered()
	{
		// Create mock request
		$request = $this->getMock('Request', array(), array(), '', FALSE);
		// Return nonexistent extension from parameter
		$request->expects($this->once())
			->method('param')
			->with('extension')
			->will($this->returnValue('nonexistent-extension'));
		// Expect redirect to 404
		$request->expects($this->once())
			->method('redirect')
			->with('admin/error/404');

		// Get the extension controller
		$controller = new Controller_Admin_Extension($request);

		// Execute the action_process method
		$controller->action_process();
	}

	/**
	 * Test that the extension controller executes
	 * an internal request to execute the requested
	 * extension and action
	 */
	public function test_ext_controller_executes_extension_request()
	{
		// Register extension with the Admin App
		Admin::register('unittest');

		// Create mock request
		$request = $this->getMock('Request', array(), array(), '', FALSE);
		// Return unittest extension from parameter
		$request->expects($this->once())
			->method('param')
			->with('extension')
			->will($this->returnValue('unittest'));

		// Get the extension controller
		$controller = new Controller_Admin_Extension($request);

		// Set requested action to 'execute'
		$controller->action = 'execute';

		// Execute the action_process method
		$controller->action_process();

		// Verify that unittest/execute was executed
		$this->assertTrue(Admin::registered('unittest-execute-successful'));
	}

	/**
	 * Provider for testing that exceptions result
	 * in 404 error redirects
	 */
	public function provider_ext_controller_redirects_404_on_exception()
	{
		return array(
			array('unittest', 'widget_exception'),
			array('unittest', 'nonexistent'),
			array('nonexistent', 'nonexistent'),
		);
	}

	/**
	 * Test that the extension controller redirects
	 * to a 404 error if the extension request
	 * throws an exception
	 *
	 * @dataProvider provider_ext_controller_redirects_404_on_exception
	 */
	public function test_ext_controller_redirects_404_on_exception($extension, $action)
	{
		// Register extension with the Admin App
		Admin::register('unittest');

		// Create mock request
		$request = $this->getMock('Request', array(), array(), '', FALSE);
		// Return unittest extension from parameter
		$request->expects($this->once())
			->method('param')
			->with('extension')
			->will($this->returnValue($extension));
		// Expect redirect to 404
		$request->expects($this->once())
			->method('redirect')
			->with('admin/error/404');

		// Get the extension controller
		$controller = new Controller_Admin_Extension($request);

		// Set requested action to 'execute'
		$controller->action = $action;

		// Execute the action_process method
		$controller->action_process();
	}

	/**
	 * Test that the extension controller redirects to login
	 * if extension result status is 403 and not logged in
	 */
	public function test_ext_controller_redirects_login_if_403_and_not_logged_in()
	{
		// Register extension with the Admin App
		Admin::register('unittest');

		// Create mock A2
		$a2 = $this->getMock('A2');
		$a2->expects($this->once())
			->method('logged_in')
			->will($this->returnValue(FALSE));

		// Create mock request
		$request = $this->getMock('Request', array(), array(), '', FALSE);
		// Return unittest extension from parameter
		$request->expects($this->once())
			->method('param')
			->with('extension')
			->will($this->returnValue('unittest'));
		// Expect redirect to login
		$request->expects($this->once())
			->method('redirect')
			->with('admin/login');

		// Get the extension controller
		$controller = new Controller_Admin_Extension($request);

		// Set requested action to 'execute'
		$controller->action = 'redirect_403';

		// Set mock A2
		$controller->a2 = $a2;

		// Execute the action_process method
		$controller->action_process();
	}

	/**
	 * Test that the extension controller redirects to previous page
	 * if extension result status is 403 and logged in
	 */
	public function test_ext_controller_redirects_previous_if_403_and_logged_in()
	{
		// Register extension with the Admin App
		Admin::register('unittest');

		// Create mock A2
		$a2 = $this->getMock('A2');
		$a2->expects($this->once())
			->method('logged_in')
			->will($this->returnValue(TRUE));

		// Set previous URL
		Request::$referrer = 'previous/url';

		// Create mock request
		$request = $this->getMock('Request', array(), array(), '', FALSE);
		// Return unittest extension from parameter
		$request->expects($this->once())
			->method('param')
			->with('extension')
			->will($this->returnValue('unittest'));
		// Expect redirect to login
		$request->expects($this->once())
			->method('redirect')
			->with('previous/url');

		// Get the extension controller
		$controller = new Controller_Admin_Extension($request);

		// Set requested action to 'execute'
		$controller->action = 'redirect_403';

		// Set mock A2
		$controller->a2 = $a2;

		// Execute the action_process method
		$controller->action_process();
	}

	/**
	 * Test that the extension controller sets flash message
	 * if user message set and result status is 403
	 */
	public function test_ext_controller_sets_flash_if_message_given_and_403()
	{
		// Register extension with the Admin App
		Admin::register('unittest');

		// Create mock request
		$request = $this->getMock('Request', array(), array(), '', FALSE);
		// Return unittest extension from parameter
		$request->expects($this->once())
			->method('param')
			->with('extension')
			->will($this->returnValue('unittest'));
		// Expect redirect to login
		$request->expects($this->once())
			->method('redirect')
			->with('admin/login');

		// Get the extension controller
		$controller = new Controller_Admin_Extension($request);

		// Set requested action to 'execute'
		$controller->action = 'message_403';

		// Execute the action_process method
		$controller->action_process();

		// Check that flash message set
		$messages = Admin::messages();
		$this->assertEquals(1, count($messages[Admin::ERROR]));
		$this->assertEquals("403 MESSAGE", $messages[Admin::ERROR][0]);
	}

	/**
	 * Test that the extension controller redirects to given URL
	 * if extension result status is 404
	 */
	public function test_ext_controller_redirects_url_if_404()
	{
		// Register extension with the Admin App
		Admin::register('unittest');

		// Create mock request
		$request = $this->getMock('Request', array(), array(), '', FALSE);
		// Return unittest extension from parameter
		$request->expects($this->once())
			->method('param')
			->with('extension')
			->will($this->returnValue('unittest'));
		// Expect redirect to login
		$request->expects($this->once())
			->method('redirect')
			->with('admin/unittest/redirect');

		// Get the extension controller
		$controller = new Controller_Admin_Extension($request);

		// Set requested action to 'execute'
		$controller->action = 'redirect_404';

		// Execute the action_process method
		$controller->action_process();
	}

	/**
	 * Test that the extension controller sets flash message
	 * if user message set and result status is 404
	 */
	public function test_ext_controller_sets_flash_if_message_given_and_404()
	{
		// Register extension with the Admin App
		Admin::register('unittest');

		// Create mock request
		$request = $this->getMock('Request', array(), array(), '', FALSE);
		// Return unittest extension from parameter
		$request->expects($this->once())
			->method('param')
			->with('extension')
			->will($this->returnValue('unittest'));

		// Get the extension controller
		$controller = new Controller_Admin_Extension($request);

		// Set requested action to 'execute'
		$controller->action = 'message_404';

		// Execute the action_process method
		$controller->action_process();

		// Check that flash message set
		$messages = Admin::messages();
		$this->assertEquals(1, count($messages[Admin::ERROR]));
		$this->assertEquals("404 MESSAGE", $messages[Admin::ERROR][0]);
	}

	/**
	 * Test that the extension controller redirects to given URL
	 * if URL is given and extension result status is 200
	 */
	public function test_ext_controller_redirects_url_if_given_and_200()
	{
		// Register extension with the Admin App
		Admin::register('unittest');

		// Create mock request
		$request = $this->getMock('Request', array(), array(), '', FALSE);
		// Return unittest extension from parameter
		$request->expects($this->once())
			->method('param')
			->with('extension')
			->will($this->returnValue('unittest'));
		// Expect redirect to login
		$request->expects($this->once())
			->method('redirect')
			->with('admin/unittest/redirect');

		// Get the extension controller
		$controller = new Controller_Admin_Extension($request);

		// Set requested action to 'execute'
		$controller->action = 'redirect_200';

		// Execute the action_process method
		$controller->action_process();

		// Check that the request status is 200
		$this->assertEquals(200, $request->status);
	}

	/**
	 * Test that the extension controller redirects to given URL
	 * if URL is given and extension result status is 400
	 */
	public function test_ext_controller_redirects_url_if_given_and_400()
	{
		// Register extension with the Admin App
		Admin::register('unittest');

		// Create mock request
		$request = $this->getMock('Request', array(), array(), '', FALSE);
		// Return unittest extension from parameter
		$request->expects($this->once())
			->method('param')
			->with('extension')
			->will($this->returnValue('unittest'));
		// Expect redirect to login
		$request->expects($this->once())
			->method('redirect')
			->with('admin/unittest/redirect');

		// Get the extension controller
		$controller = new Controller_Admin_Extension($request);

		// Set requested action to 'execute'
		$controller->action = 'redirect_400';

		// Execute the action_process method
		$controller->action_process();
	}

	/**
	 * Test that the extension controller sets flash message
	 * if user message set and result status is 200
	 */
	public function test_ext_controller_sets_flash_if_message_given_and_200()
	{
		// Register extension with the Admin App
		Admin::register('unittest');

		// Create mock request
		$request = $this->getMock('Request', array(), array(), '', FALSE);
		// Return unittest extension from parameter
		$request->expects($this->once())
			->method('param')
			->with('extension')
			->will($this->returnValue('unittest'));

		// Get the extension controller
		$controller = new Controller_Admin_Extension($request);

		// Set requested action to 'execute'
		$controller->action = 'message_200';

		// Execute the action_process method
		$controller->action_process();

		// Check that the request status is 200
		$this->assertEquals(200, $request->status);

		// Check that flash message set
		$messages = Admin::messages('info');
		$this->assertEquals(1, count($messages[Admin::INFO]));
		$this->assertEquals("200 MESSAGE", $messages[Admin::INFO][0]);
	}

	/**
	 * Test that the extension controller sets flash message
	 * if user message set and result status is 400
	 */
	public function test_ext_controller_sets_flash_if_message_given_and_400()
	{
		// Register extension with the Admin App
		Admin::register('unittest');

		// Create mock request
		$request = $this->getMock('Request', array(), array(), '', FALSE);
		// Return unittest extension from parameter
		$request->expects($this->once())
			->method('param')
			->with('extension')
			->will($this->returnValue('unittest'));

		// Get the extension controller
		$controller = new Controller_Admin_Extension($request);

		// Set requested action to 'execute'
		$controller->action = 'message_400';

		// Execute the action_process method
		$controller->action_process();

		// Check that the request status is 400
		$this->assertEquals(400, $request->status);

		// Check that flash message set
		$messages = Admin::messages();
		$this->assertEquals(1, count($messages[Admin::ERROR]));
		$this->assertEquals("400 MESSAGE", $messages[Admin::ERROR][0]);
	}

	/**
	 * Test that the extension controller sets HTML response
	 * if no redirect URL given and result status is 200
	 */
	public function test_ext_controller_sets_response_if_no_url_and_200()
	{
		// Register extension with the Admin App
		Admin::register('unittest');

		// Create mock request
		$request = $this->getMock('Request', array(), array(), '', FALSE);
		// Return unittest extension from parameter
		$request->expects($this->once())
			->method('param')
			->with('extension')
			->will($this->returnValue('unittest'));

		// Get the extension controller
		$controller = new Controller_Admin_Extension($request);

		// Set requested action to 'execute'
		$controller->action = 'response_200';

		// Execute the action_process method
		$controller->action_process();

		// Check that the request status is 200
		$this->assertEquals(200, $request->status);

		// Check that response is set
		$this->assertEquals("200 RESPONSE", $request->response);
	}

	/**
	 * Test that the extension controller sets HTML response
	 * if no redirect URL given and result status is 400
	 */
	public function test_ext_controller_sets_response_if_no_url_and_400()
	{
		// Register extension with the Admin App
		Admin::register('unittest');

		// Create mock request
		$request = $this->getMock('Request', array(), array(), '', FALSE);
		// Return unittest extension from parameter
		$request->expects($this->once())
			->method('param')
			->with('extension')
			->will($this->returnValue('unittest'));

		// Get the extension controller
		$controller = new Controller_Admin_Extension($request);

		// Set requested action to 'execute'
		$controller->action = 'response_400';

		// Execute the action_process method
		$controller->action_process();

		// Check that the request status is 400
		$this->assertEquals(400, $request->status);

		// Check that response is set
		$this->assertEquals("400 RESPONSE", $request->response);
	}

	/**
	 * Test that the request action is reset
	 */
	public function test_request_action_is_reset()
	{
		// Register extension with the Admin App
		Admin::register('unittest');

		// Create mock request
		$request = $this->getMock('Request', array(), array(), '', FALSE);
		// Set requested action to 'execute'
		$request->action = 'response_200';

		// Get the extension controller
		$controller = new Controller_Admin_Extension($request);

		// Execute the before method
		$controller->before();

		// Verify action reset
		$this->assertEquals('process', $request->action);
		$this->assertEquals('response_200', $controller->action);
	}

}	// End of Admin_Controller_ExtensionTest
