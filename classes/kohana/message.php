<?php defined('SYSPATH') OR die('No direct script access.');

/**
 * Flash message handler
 *
 * @package     Kohana
 * @category    Base
 * @author      Kyle Treubig
 * @copyright   (c) 2010 Kyle Treubig
 * @license     MIT
 */
abstract class Kohana_Message {

	/** Config */
	private $_config;

	/** Cached info message */
	private $info;

	/** Cached error message */
	private $error;

	public function __construct() {
		$this->_config = Kohana::config('message');
		Kohana::$log->add(Kohana::DEBUG, 'Message class instantiated.');
	}

	public static function instance() {
		static $instance;
		if ( ! isset($instance))
		{
			$instance = new Message;
		}
		return $instance;
	}

	public function info($msg, array $values=NULL, $lang='en-us') {
		$session = Session::instance($this->_config['session_type']);
		$session->set('flash_msg_info', __($msg, $values, $lang));
	}

	public function error($msg, array $values=NULL, $lang='en-us') {
		$session = Session::instance($this->_config['session_type']);
		$session->set('flash_msg_error', __($msg, $values, $lang));
	}

	public function get($type=NULL) {
		if ($type === NULL)
		{
			return $this->get('info').$this->get('error');
		}
		else
		{
			$session = Session::instance($this->_config['session_type']);
			$msg = $session->get('flash_msg_'.$type, FALSE);
			if ($msg !== FALSE)
			{
				$session->delete('flash_msg_'.$type);
				$this->{$type} = $msg;

				$return = $this->_config['tags'][$type]['open'];
				$return .= $msg;
				$return .= $this->_config['tags'][$type]['close'];
				return $return;
			}
			else
			{
				return '';
			}
		}
	}

}

