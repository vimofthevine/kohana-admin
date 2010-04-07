<?php defined('SYSPATH') OR die('No direct script access.');

/**
 * @package     Controller
 * @author      Kyle Treubig
 * @copyright   (c) 2010 Kyle Treubig
 * @license     MIT
 */
class Controller_Admin_Support extends Controller_Template_Admin {

	/**
	 * Register controller as an admin controller
	 */
	public function before() {
		parent::before();
	}

	/**
	 * Display support menu
	 */
	public function action_menu() {
		if ( ! $this->internal_request)
		{
			Request::instance()->redirect(Route::get('admin_main')->uri());
		}
		$this->template->content = new View('admin/support/widget/menu');
	}

	/**
	 * Create a bug report
	 */
	public function action_bug() {
		Message::instance()->info('Bug report functionality is coming in a future version.');
		Request::instance()->redirect( Route::get('admin_main')->uri() );
	}

	/**
	 * Open a support ticket
	 */
	public function action_contact() {
		Message::instance()->info('Support ticket functionality is coming in a future version.');
		Request::instance()->redirect( Route::get('admin_main')->uri() );
	}

}

