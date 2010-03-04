<?php defined('SYSPATH') OR die('No direct script access.');

class Controller_Admin_Main extends Controller_Template_Admin {
	public function action_index() {
		$this->template->content = "hello world";
	}
}

