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
