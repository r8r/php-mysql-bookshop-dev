<?php

namespace Bookshop;

class Controller extends BaseObject {

	public const ACTION = 'action';
	public const PAGE = 'page';
	public const ACTION_ADD = 'addToCart';
	public const ACTION_REMOVE = 'removeFromCart';
	public const ACTION_ORDER = 'placeOrder';
	public const ACTION_LOGIN = 'login';
	public const ACTION_LOGOUT = 'logout';
	public const USER_NAME = 'userName';
	public const USER_PASSWORD = 'password';

	private static $instance = false;

	public static function getInstance() : Controller {
		if (!self::$instance) {
			self::$instance = new Controller();
		}
		return self::$instance;
	}

	public function invokePostAction() {
		if ($_SERVER['REQUEST_METHOD'] != 'POST') {
			throw new \Exception('Controller can only handle POST requests.');
		}
		else if (!isset($_REQUEST[self::ACTION])) {
			throw new \Exception('Action parameter not specified');
		}

		$action = $_REQUEST[self::ACTION];

		switch ($action) {
			case self::ACTION_ADD:
				ShoppingCart::add((int)$_REQUEST['bookId']);
				Util::redirect();
				break;
			case self::ACTION_REMOVE:
				ShoppingCart::remove((int)$_REQUEST['bookId']);
				Util::redirect();
				break;
			case self::ACTION_ORDER:
				print "place order";
				// todo
				break;
			case self::ACTION_LOGIN:
				print "log in";
				// todo
				break;
			case self::ACTION_LOGOUT:
				print "log out";
				// todo
				break;
			default:
				throw new \Exception('Unknown controller action: ' . $action);
				// todo
		}


	}

}