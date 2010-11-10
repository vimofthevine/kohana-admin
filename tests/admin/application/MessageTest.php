<?php defined('SYSPATH') or die('No direct script access.');

/**
 * PHPUnit tests for the Admin App's
 * user message (flash messages) helper
 * functions. All tests are written as
 * one spec per test.
 *
 * @see         http://github.com/vimofthevine/kohana-admin/wiki/Software-Requirements-Specification
 *
 * @group       admin
 * @group       admin.application
 * @group       admin.application.message
 *
 * @package     Admin
 * @category    Tests
 * @author      Kyle Treubig
 * @copyright   (C) 2010 Kyle Treubig
 * @license     MIT
 */
class Admin_Application_MessageTest extends Kohana_Unittest_TestCase {

	/**
	 * Reset the Admin App before performing all tests
	 */
	public function setUp()
	{
		Admin::reset();

		// Get session
		$this->session = Session::instance(Kohana::config('admin.messages.session.type'));

		// Get session key
		$this->key = Kohana::config('admin.messages.session.key');

		// Remove messages
		$this->session->delete($this->key);
	}

	/**
	 * Test that the Admin App stores messages
	 * in a session
	 */
	public function test_messages_stored_in_session()
	{
		// Store message
		Admin::message('TEST SESSION MESSAGE');

		// Get message from session
		$session = $this->session->get($this->key, FALSE);

		// Verify session value set
		$this->assertFALSE(empty($session));
	}

	/**
	 * Test that the Admin App stores messages
	 * as a serialized array
	 */
	public function test_messages_stored_as_serialized_array()
	{
		$msg = 'TEST SERIALIZED ARRAY';

		// Store message
		Admin::message($msg);

		// Get message from session
		$message = unserialize($this->session->get($this->key));

		// Verify message
		$this->assertContains($msg, Arr::flatten($message));
	}

	/**
	 * Test that the Admin App stores info message
	 */
	public function test_info_message()
	{
		// Store message
		$msg = 'TEST INFO MESSAGE';
		Admin::message($msg, Admin::INFO);

		// Get message from session
		$message = unserialize($this->session->get($this->key));

		// Verify message
		$this->assertEquals($msg, $message[Admin::INFO][0]);
	}

	/**
	 * Test that the Admin App stores error message
	 */
	public function test_error_message()
	{
		// Store message
		$msg = 'TEST ERROR MESSAGE';
		Admin::message($msg, Admin::ERROR);

		// Get message from session
		$message = unserialize($this->session->get($this->key));

		// Verify message
		$this->assertEquals($msg, $message[Admin::ERROR][0]);
	}

	/**
	 * Test that the Admin App deletes messages
	 */
	public function test_messages_are_deleted()
	{
		// Store messages
		Admin::message('TEST MESSAGE 1', Admin::INFO);
		Admin::message('TEST MESSAGE 2', Admin::ERROR);

		// Get messages
		$messages = Admin::messages();

		// Get messages from session
		$session = $this->session->get($this->key, FALSE);

		// Verify messages deleted from session
		$this->assertFalse($session);
	}

}	// End of Admin_Application_MessageTest
