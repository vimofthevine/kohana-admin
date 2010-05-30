<?php defined('SYSPATH') OR die('No direct script access.');

/**
 * Main Admin Controller (dashboard)
 *
 * @package     Admin
 * @category    Controller
 * @author      Kyle Treubig
 * @copyright   (c) 2010 Kyle Treubig
 * @license     MIT
 */
class Controller_Admin_Main extends Controller_Admin {

	protected $_view_map = array(
		'index' => 'admin/layout/none',
	);

	protected $_current_nav = 'admin';

	/**
	 * Display administration dashboard
	 */
	public function action_index() {
		if ($this->a1->get_user() === FALSE)
		{
			$this->request->redirect( Route::get('admin/auth')->uri() );
		}

		$this->template->scripts[] = Route::get('admin/media')->uri(array('file'=>'ThePixelDeveloper_Admin-Template/js/glow/1.7.0/core/core.js'));
		$this->template->scripts[] = Route::get('admin/media')->uri(array('file'=>'ThePixelDeveloper_Admin-Template/js/glow/1.7.0/widgets/widgets.js'));
		$this->template->scripts[] = Route::get('media')->uri(array('file'=>'js/dashboard_widgets.js'));
		$this->template->styles[ Route::get('admin/media')->uri(array('file'=>'ThePixelDeveloper_Admin-Template/js/glow/1.7.0/widgets/widgets.css')) ] = 'screen';

		$this->template->content = View::factory('admin/dashboard')
			->set('user_info', $this->user_info())
			->set('system_info', $this->system_info())
			->set('updates', $this->updates());
	}

	/**
	 * Display current user information
	 */
	private function user_info() {
		return View::factory('admin/dashboard/user_info')
			->set('user', $this->a2->get_user())
			->set('ip', Request::$client_ip);
	}

	/**
	 * Display system information
	 */
	private function system_info() {
		$view = View::factory('admin/dashboard/system_info')
			->bind('apache_version', $apache_version)
			->bind('php_version', $php_version)
			->bind('mod_ssl_version', $mod_ssl_version)
			->bind('openssl_version', $openssl_version)
			->bind('dav_version', $dav_version)
			->bind('mysql_version', $mysql_version)
			->bind('app_version', $app_version)
			->set('kohana_version', Kohana::VERSION);

		$app_version = defined('APP_VERSION') ? APP_VERSION : '0.1';

		try
		{
			Database::instance()->connect();
			$mysql_version = mysql_get_server_info();
			$mysql_version = $mysql_version ? $mysql_version : 'unavailable';
		}
		catch (Database_Exception $e)
		{
			$view->mysql_version = 'unavailable';
		}

		$sw = preg_split("/[\/ ]/", $_SERVER['SERVER_SOFTWARE']);
		for ($i=0; $i<count($sw); $i++)
		{
			switch (strtoupper($sw[$i]))
			{
			case 'APACHE':
				$i++;
				$apache_version = $sw[$i];
				break;
			case 'PHP':
				$i++;
				$php_version = $sw[$i];
				break;
			case 'MOD_SSL':
				$i++;
				$mod_ssl_version = $sw[$i];
				break;
			case 'OPENSSL':
				$i++;
				$openssl_version = $sw[$i];
				break;
			case 'DAV':
				$i++;
				$dav_version = $sw[$i];
				break;
			}
		}

		return $view;
	}

	/**
	 * Show recent updates
	 */
	private function updates() {
		return View::factory('admin/updates');
	}

}

