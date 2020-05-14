<?php
/**
 * Created by PhpStorm.
 * User: r8r
 * Date: 2020-03-28
 * Time: 14:49
 */

namespace Bookshop;


use Data\DataManager;
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
	const CC_NAME = 'nameOnCard';
	const CC_NUMBER = 'cardNumber';

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

			case self::ACTION_ORDER:
				$user = AuthenticationManager::getAuthenticatedUser();
				if ($user == null) {
					$this->forwardRequest(['Not logged in.']);
				}
				if (!$this->processCheckout($_REQUEST[self::CC_NAME], $_REQUEST[self::CC_NUMBER])) {
					$this->forwardRequest(['Checkout failed.']);
				}
				break;

			default:
				break;
		}

		return false;

	}

	protected function processCheckout(string $nameOnCard = null, string $cardNumber = null) : bool {
		$return = false;

		$errors = [];

		if ($nameOnCard == null || strlen($nameOnCard) == 0) {
			$errors[] = 'Invalid name on card.';
		}
		if ($cardNumber == null || strlen($cardNumber) != 16 || !ctype_digit($cardNumber)) {
			$errors[] = 'Invalid card number. Must be sixteen digits long.';
		}

		if (sizeof($errors) > 0) {
			$this->forwardRequest($errors);
			return false;
		}

		if (ShoppingCart::size() == 0) {
			$this->forwardRequest(['Shopping cart is empty']);
			return false;
		}

		$user = AuthenticationManager::getAuthenticatedUser();

		$orderId = DataManager::createOrder($user->getid(), ShoppingCart::getAll(), $nameOnCard, $cardNumber);

		if (!$orderId) {
			$this->forwardRequest(['Could not create order.']);
			return false;
		}
		$return = true;
		ShoppingCart::clear();
		Util::redirect('index.php?view=success&orderId=' . rawurlencode($orderId));
		return $return;
	}

	/**
	 *
	 * @param array $errors : optional assign it to
	 * @param string $target : url for redirect of the request
	 */
	protected function forwardRequest(array $errors = null, $target = null) {
		//check for given target and try to fall back to previous page if needed
		if ($target == null) {
			if (!isset($_REQUEST[self::PAGE])) {
				throw new Exception('Missing target for forward.');
			}
			$target = $_REQUEST[self::PAGE];
		}
		//forward request to target
		// optional - add errors to redirect and process them in view
		if (count($errors) > 0) {
			$target .= '&errors=' . urlencode(serialize($errors));
		}
		header('location: ' . $target);
		exit();
	}


}