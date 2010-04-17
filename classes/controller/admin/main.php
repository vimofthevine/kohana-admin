<?php defined('SYSPATH') OR die('No direct script access.');

class Controller_Admin_Main extends Controller_Template_Admin {

	public function action_index() {
		if ($this->a1->get_user() === FALSE)
		{
			Request::instance()->redirect( Route::get('admin_auth')->uri(array('action'=>'login')) );
		}

		unset($this->template->menu->menu['Home'][0]);

		$this->template->scripts[] = Route::get('admin_media')->uri(array('file'=>'ThePixelDeveloper_Admin-Template/js/glow/1.7.0/core/core.js'));
		$this->template->scripts[] = Route::get('admin_media')->uri(array('file'=>'ThePixelDeveloper_Admin-Template/js/glow/1.7.0/widgets/widgets.js'));
		$this->template->scripts[] = Route::get('media')->uri(array('file'=>'js/dashboard_widgets.js'));
		$this->template->styles[ Route::get('admin_media')->uri(array('file'=>'ThePixelDeveloper_Admin-Template/js/glow/1.7.0/widgets/widgets.css')) ] = 'screen';
		$this->template->content = new View('admin/dashboard');
	}

	public function action_user_info() {
		if ( ! $this->internal_request)
		{
			Request::instance()->redirect( Route::get('admin_main')->uri() );
		}

		$view = new View('admin/dashboard/user_info');
		$view->user = $this->a1->get_user();
		$view->ip = isset($_SERVER['HTTP_X_FORWARD_FOR'])
			? $_SERVER['HTTP_X_FORWARD_FOR']
			: $_SERVER['REMOTE_ADDR'];
		$this->template->content = $view;
	}

	public function action_system_info() {
		if ( ! $this->internal_request)
		{
			Request::instance()->redirect( Route::get('admin_main')->uri() );
		}

		$view = new View('admin/dashboard/system_info');

		$sw = preg_split("/[\/ ]/", $_SERVER['SERVER_SOFTWARE']);
		for ($i=0; $i<count($sw); $i++)
		{
			switch (strtoupper($sw[$i]))
			{
			case 'APACHE':
				$i++;
				$view->apache_version = $sw[$i];
				break;
			case 'PHP':
				$i++;
				$view->php_version = $sw[$i];
				break;
			case 'MOD_SSL':
				$i++;
				$view->mod_ssl_version = $sw[$i];
				break;
			case 'OPENSSL':
				$i++;
				$view->openssl_version = $sw[$i];
				break;
			case 'DAV':
				$i++;
				$view->dav_version = $sw[$i];
				break;
			}
		}

		$view->mysql_version = mysql_get_server_info();

		$this->template->content = $view;
	}

	public function action_updates() {
		if ( ! $this->internal_request)
		{
			Request::instance()->redirect( Route::get('admin_main')->uri() );
		}

		$view = new View('admin/updates');
		$this->template->content = $view;
	}

}

