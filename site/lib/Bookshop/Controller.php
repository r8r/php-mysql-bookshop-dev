<?php

namespace Bookshop;

use Data\DataManager;

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
	public const CC_NAME = 'name_on_card';
	public const CC_NUMBER = 'cc_number';


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
				$user = AuthenticationManager::getAuthenticatedUser();

				if ($user === null) {
					$this->forwardRequest(['user ist not logged in.']);
					exit();
				}

				if ($this->processCheckout($_REQUEST[self::CC_NAME], $_REQUEST[self::CC_NUMBER])) {

					DataManager::createOrder($user->getId(), $_SESSION['cart'], $_REQUEST[self::CC_NAME], $_REQUEST[self::CC_NUMBER]);


				}

				break;
			case self::ACTION_LOGIN:
				if (!AuthenticationManager::authenticate($_REQUEST[self::USER_NAME], $_REQUEST[self::USER_PASSWORD])) {
					$this->forwardRequest(['Invalid credentials!']);
				}
				Util::redirect('index.php');
				break;
			case self::ACTION_LOGOUT:
				AuthenticationManager::signOut();

				break;
			default:
				throw new \Exception('Unknown controller action: ' . $action);
				// todo
		}


	}

	/**
	 *
	 * @param array $errors : optional assign it to
	 * @param string $target : url for redirect of the request
	 */
	protected function forwardRequest(array $errors = null, string $target = null) : never {
		//check for given target and try to fall back to previous page if needed
		if ($target == null) {
			if (!isset($_REQUEST[self::PAGE])) {
				throw new \Exception('Missing target for forward.');
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

	/**
	 *
	 * @param string $nameOnCard
	 * @param integer $cardNumber
	 * @return bool
	 */
	protected function processCheckout(string $nameOnCard = null, string $cardNumber = null) : bool {

		$errors     = [];
		$nameOnCard = trim($nameOnCard);
		if ($nameOnCard == null || strlen($nameOnCard) == 0) {
			$errors[] = 'Invalid name on card.';
		}
		if ($cardNumber == null || strlen($cardNumber) != 16 || ! ctype_digit($cardNumber)) {
			$errors[] = 'Invalid card number. Card number must be sixteen digits.';
		}

		if (sizeof($errors) > 0) {
			$this->forwardRequest($errors);

			return false;
		}

		//check cart
		if (ShoppingCart::size() == 0) {
			$this->forwardRequest(['Shopping cart is empty.', 'asgdaisdhj', 'asdasds']);
			return false;
		}

		//try to place a new order
		$user = AuthenticationManager::getAuthenticatedUser();
		$orderId = \Data\DataManager::createOrder($user->getId(), ShoppingCart::getAll(), $nameOnCard, $cardNumber);
		if (!$orderId) {
			$this->forwardRequest(['Could not create order.']);
			return false;
		}
		//clear shopping card and redirect to success page
		ShoppingCart::clear();
		Util::redirect('index.php?view=success&orderId=' . rawurlencode($orderId));

		return true;
	}

}