<?php
/**
 * Created by PhpStorm.
 * User: r8r
 * Date: 2019-05-25
 * Time: 08:40
 */

namespace Bookshop;


class Controller extends BaseObject {

	const ACTION = 'action';
	const PAGE = 'page';
	const ACTION_ADD = 'addToCart';
	const ACTION_REMOVE = 'removeFromCart';
	const ACTION_ORDER = 'placeOrder';
	const ACTION_LOGIN = 'login';
	const ACTION_LOGOUT = 'logout';

	private static $instance = false;

	public static function getInstance() : Controller {
		if (!self::$instance) {
			self::$instance = new Controller();
		}
		return self::$instance;
	}

	private function __construct() {}

	public function invokePostAction() : bool {

		if ($_SERVER['REQUEST_METHOD'] != 'POST') {
			throw new \Exception('Controller can only handle POST requests.');
			return null;
		}
		elseif (!isset($_REQUEST[self::ACTION])) {
			throw new \Exception(self::ACTION . ' not specified.');
			return null;
		}

		// now process the assigned action
		$action = $_REQUEST[self::ACTION];

		switch ($action) {

			case self::ACTION_ADD :
				break;

			case self::ACTION_REMOVE :
				break;

			case self::ACTION_ORDER :
				break;

			case self::ACTION_LOGIN :
				break;

			case self::ACTION_LOGOUT :
				break;

			default :
				throw new \Exception('Unknown controller action: ' . $action);
				return null;
				break;
		}

		return false;
	}


}