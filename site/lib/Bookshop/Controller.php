<?php

namespace Bookshop;

class Controller extends BaseObject {

	public const ACTION = 'action';
	public const PAGE = 'page';
	public const ACTION_ADD = 'addToCart';
	public const ACTION_REMOVE = 'removeFromCart';

	private static $instance = false;

	/**
	 *
	 * @return Controller
	 */
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
		elseif (!isset($_REQUEST[self::ACTION])) {
			throw new \Exception(self::ACTION . ' not specified.');
		}

		// now process the assigned action
		$action = $_REQUEST[self::ACTION];

		switch ($action) {

			case self::ACTION_ADD :
				ShoppingCart::add((int) $_REQUEST['bookId']);
				Util::redirect();
				break;

			case self::ACTION_REMOVE :
				ShoppingCart::remove((int) $_REQUEST['bookId']);
				Util::redirect();
				break;

			default :
				throw new \Exception('Unknown controller action: ' . $action);
				break;
		}
	}
}