<?php

namespace Bookshop;

use Data\DataManager;

class Controller extends BaseObject {

	const PAGE = 'page';
	const ACTION = 'action';
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
	public static function getInstance() : Controller {

		if (!self::$instance) {
			self::$instance = new Controller();
		}

		return self::$instance;
	}

	private function __construct() {

	}

	//public function invokePostAction() : never {
	public function invokePostAction() {

		// sicherstellen, dass POST verwendet wurde
		if ($_SERVER['REQUEST_METHOD'] != 'POST') {
			throw new \Exception('Controller can only handle POST requests.');
		}
		// sicherstellen, dass ACTION existiert
		elseif (!isset($_REQUEST[self::ACTION])) {
			throw new \Exception(self::ACTION . ' is not defined.');
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

			case self::ACTION_LOGIN:
				if (!AuthenticationManager::authenticate($_REQUEST[self::USER_NAME], $_REQUEST[self::USER_PASSWORD])) {
					$this->forwardRequest(['Invalid user name or password']);
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
					$this->forwardRequest(['You are not logged in.']);
				}
				else if (!$this->processCheckout($_POST[self::CC_NAME], $_REQUEST[self::CC_NUMBER])) {
					$this->forwardRequest(['Checkout failed.']);
				}

				break;

			default:
				throw new \Exception('Unknown controller action: ' . $action);
				break;
		}

	}

	private function processCheckout(string $nameOnCard = null, string $cardNumber = null) : bool {

		$errors = [];
		if ($nameOnCard == null || strlen(trim($nameOnCard)) === 0) {
			$errors[] = "Invalid name on card.";
		}
		if ($cardNumber == null || strlen(trim($cardNumber)) !== 16 || !ctype_digit($cardNumber)) {
			$errors[] = "Invalid card number. Card number must be sixteen digits.";
		}

		if (sizeof($errors)) {
			$this->forwardRequest($errors);
			return false;
		}

		if (ShoppingCart::size() == 0) {
			$this->forwardRequest(['Shopping cart is empty.']);
			return false;
		}

		$user = AuthenticationManager::getAuthenticatedUser();
		$orderId = DataManager::createOrder($user->getId(), ShoppingCart::getAll(), $nameOnCard, $cardNumber);

		if (!$orderId) {
			$this->forwardRequest(['Checkout failed.']);
			return false;
		}

		ShoppingCart::clearCart();
		Util::redirect('index.php?view=success&orderId=' . rawurlencode($orderId));

		return true;
	}

	private function forwardRequest(array $errors, string $page = null) {
		if ($page == null) {
			if (!isset($_REQUEST[self::PAGE])) {
				throw new \Exception('Missing page to forward to.');
			}
			$page = $_REQUEST[self::PAGE];
		}

		if (count($errors) > 0) {
			$page .= '&errors=' . urlencode(serialize($errors));
		}

		header('Location:' . $page);
	}


}