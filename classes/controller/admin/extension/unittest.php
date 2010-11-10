<?php defined('SYSPATH') or die('No direct script access.');

/**
 * Test extension used for unit testing purposes
 *
 * @package     Admin
 * @category    Tests
 * @author      Kyle Treubig
 * @copyright   (C) 2010 Kyle Treubig
 * @license     MIT
 */
class Controller_Admin_Extension_Unittest extends Controller {

	public function action_execute()
	{
		Admin::register('unittest-execute-successful');
	}

	public function action_redirect_200()
	{
		$this->request->status = 200;
		$this->request->redirect = 'admin/unittest/redirect';
	}

	public function action_response_200()
	{
		$this->request->response = '200 RESPONSE';
	}

	public function action_message_200()
	{
		$this->request->message = '200 MESSAGE';
	}

	public function action_redirect_400()
	{
		$this->request->status = 400;
		$this->request->redirect = 'admin/unittest/redirect';
	}

	public function action_message_400()
	{
		$this->request->status  = 400;
		$this->request->message = '400 MESSAGE';
	}

	public function action_response_400()
	{
		$this->request->status   = 400;
		$this->request->response = '400 RESPONSE';
	}

	// TODO rename
	public function action_redirect_403()
	{
		$this->request->status = 403;
	}

	public function action_message_403()
	{
		$this->request->status  = 403;
		$this->request->message = '403 MESSAGE';
	}

	public function action_redirect_404()
	{
		$this->request->status = 404;
		$this->request->redirect = 'admin/unittest/redirect';
	}

	public function action_message_404()
	{
		$this->request->status  = 404;
		$this->request->message = '404 MESSAGE';
	}

	public function action_widget_200()
	{
		$this->request->status   = 200;
		$this->request->response = "WIDGET 200";
	}

	public function action_widget_301()
	{
		$this->request->status = 301;
	}

	public function action_widget_400()
	{
		$this->request->status   = 400;
		$this->request->response = "WIDGET 400";
	}

	public function action_widget_403()
	{
		$this->request->status = 403;
	}

	public function action_widget_404()
	{
		$this->request->status = 404;
	}

	public function action_widget_exception()
	{
		throw new Kohana_Exception('Unit test exception');
	}

	public function action_widget_message()
	{
		$this->request->message = "USER MESSAGE";
	}

}	// End of Controller_Admin_Extension_Unittest
