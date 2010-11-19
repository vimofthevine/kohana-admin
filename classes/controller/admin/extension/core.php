<?php defined('SYSPATH') or die('No direct script access.');

/**
 * Extension controller for the Admin Application.
 * Handles requests for extensions and post-action
 * results (redirects, flash messages, layout injectsions).
 *
 * @package     Admin
 * @category    Controllers
 * @author      Kyle Treubig
 * @copyright   (C) 2010 Kyle Treubig
 * @license     MIT
 */
class Controller_Admin_Extension_Core extends Controller {

	/**
	 * @var object  Instance of A2
	 */
	public $a2 = NULL;

	/**
	 * @var string  The requested extension action
	 */
	public $action;

	/**
	 * Redirect the request action to process the
	 * extension request
	 */
	public function before()
	{
		$this->action = $this->request->action;
		$this->request->action = 'process';
	}

	/**
	 * Route the request to the appropriate extension
	 */
	public function action_process()
	{
		// Get requested extension
		$extension = $this->request->param('extension');

		// Check if requested extension is registered
		if ( ! Admin::registered($extension))
		{
			// Redirect to 404
			$this->request->redirect(Route::get('admin/error')->uri());
		}
		else
		{
			// Execute the extension request
			try
			{
				$request = Request::factory(Route::get('admin/extension')
					->uri(array('controller' => $extension, 'action' => $this->action)));
				$request->execute();

				// If authorization error
				if ($request->status == 403)
				{
					// Get A2 instance
					if ($this->a2 == NULL)
					{
						$this->a2 = A2::instance(Kohana::config('admin.a2'));
					}

					// Check if message set
					if (isset($request->message) AND ! empty($request->message))
					{
						Admin::message($request->message, Admin::ERROR);
					}

					// Check if user is logged in
					if ( ! $this->a2->logged_in())
					{
						// Redirect to login
						$this->request->redirect(Route::get('admin/auth')->uri());
					}
					else
					{
						// Redirect to previous page
						$this->request->redirect(Request::$referrer);
					}
				}
				// If not-found error
				elseif ($request->status == 404)
				{
					// Check if message set
					if (isset($request->message) AND ! empty($request->message))
					{
						Admin::message($request->message, Admin::ERROR);
					}

					// Check if redirect URL set
					if (isset($request->redirect) AND ! empty($request->redirect))
					{
						// Redirect to URL
						$this->request->redirect($request->redirect);
					}
					else
					{
						// Redirect to error
						$this->request->redirect(Route::get('admin/error')->uri());
					}
				}
				// Else 200 or 400
				else
				{
					// Set the request status
					$this->request->status = $request->status;

					// Check if message set
					if (isset($request->message) AND ! empty($request->message))
					{
						$type = ($request->status == 200) ? Admin::INFO : Admin::ERROR;
						Admin::message($request->message, $type);
					}

					// Check if redirect URL set
					if (isset($request->redirect) AND ! empty($request->redirect))
					{
						// Redirect to URL
						$this->request->redirect($request->redirect);
					}

					// Set the request response
					$this->request->response = $request->response;
				}
			}
			catch (Exception $e)
			{
				Kohana::$log->add(Kohana::ERROR, 'Error exception occured executing :ext->:action, :exception',
					array(':ext' => $extension, ':action' => $this->action, ':exception' => $e->getMessage()));

				// Redirect to 404
				$this->request->redirect(Route::get('admin/error')->uri());
			}
		}
	}

}	// End of Controller_Admin_Extension_Core
