<?php
/**
 * Created by PhpStorm.
 * User: r8r
 * Date: 2020-03-28
 * Time: 14:49
 */

namespace Bookshop;


use http\Exception\BadMethodCallException;
use mysql_xdevapi\Exception;

class Controller extends BaseObject {

	const ACTION = 'action';
	const PAGE = 'page';
	const ACTION_ADD = 'addToCart';
	const ACTION_REMOVE = 'removeFromCart';
	const ACTION_LOGIN = 'login';
	const ACTION_LOGOUT = 'logout';
	const ACTION_ORDER = 'placeOrder';
	const USER_NAME = 'userName';
	const USER_PASSWORD = 'password';

	private static $instance = false;

	private function __construct() {

	}

	public static function getInstance() : Controller {
		if (!self::$instance) {
			self::$instance = new Controller();
		}
		return self::$instance;
	}

	public function invokePostAction() : bool {

		if ($_SERVER['REQUEST_METHOD'] != 'POST') {
			throw new \Exception('Controller can only handle POST requests.');
			return null;
		}
		elseif (!isset($_REQUEST[self::ACTION])) {
			throw new \Exception(self::ACTION . 'not specified.');
			return null;
		}

		$action = $_REQUEST[self::ACTION];

		switch ($action) {

			case self::ACTION_ADD:
				ShoppingCart::add((int) $_REQUEST['bookId']);
				Util::redirect();
				break;

			case self::ACTION_REMOVE:
				ShoppingCart::remove((int) $_REQUEST['bookId']);
				Util::redirect();
				break;

			case self::ACTION_LOGIN:
				if (!AuthenticationManager::authenticate($_REQUEST[self::USER_NAME], $_REQUEST[self::USER_PASSWORD])) {
					die('error'); //TODO
				}
				Util::redirect();
				break;

			case self::ACTION_LOGOUT:
				AuthenticationManager::signOut();
				Util::redirect();
				break;

			default:
				break;
		}

		return false;

	}

}