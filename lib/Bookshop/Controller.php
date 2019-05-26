<?php
/**
 * Created by PhpStorm.
 * User: r8r
 * Date: 2019-05-25
 * Time: 08:40
 */

namespace Bookshop;

use Data\DataManager;

class Controller extends BaseObject {

	const ACTION = 'action';
	const PAGE = 'page';
	const ACTION_ADD = 'addToCart';
	const ACTION_REMOVE = 'removeFromCart';
	const ACTION_ORDER = 'placeOrder';
	const ACTION_LOGIN = 'login';
	const ACTION_LOGOUT = 'logout';
	const USER_NAME = 'username';
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
				ShoppingCart::add((int) $_REQUEST['bookId']);
				Util::redirect();
				break;

			case self::ACTION_REMOVE :
				ShoppingCart::remove((int) $_REQUEST['bookId']);
				Util::redirect();
				break;

			case self::ACTION_ORDER :
				$user = AuthenticationManager::getAuthenticatedUser();
				if (!$user) {
					$this->forwardRequest(['Not logged in!']);
				}

				if (!$this->processCheckout($_REQUEST[self::CC_NAME], $_REQUEST[self::CC_NUMBER])) {
					$this->forwardRequest(['Checkout failed!']);
				}
				break;

			case self::ACTION_LOGIN :
				if (!AuthenticationManager::authenticate($_REQUEST[self::USER_NAME], $_REQUEST[self::USER_PASSWORD])) {
					$this->forwardRequest(['Invalid user name or password.']);
				}
				break;

			case self::ACTION_LOGOUT :
				AuthenticationManager::signOut();
				Util::redirect();
				break;

			default :
				throw new \Exception('Unknown controller action: ' . $action);
				return null;
				break;
		}

		return false;
	}

	protected function processCheckout(string $nameOnCard, string $cardNumber) : bool {
		$errors = [];

		if ($nameOnCard == null || strlen($nameOnCard) == 0) {
			$errors[] = 'Invalid name on card.';
		}
		if ($cardNumber == null || strlen($cardNumber) != 16 || !ctype_digit($cardNumber)) {
			$errors[] = 'Invalid card number. Card number must be 16 digits.';
		}
		if (sizeof($errors)) {
			$this->forwardRequest($errors);
			return false;
		}

		if (ShoppingCart::size() == 0) {
			$this->forwardRequest(['Shopping cart is empty!']);
			return false;
		}

		$user = AuthenticationManager::getAuthenticatedUser();

		$orderId = DataManager::createOrder($user->getId(), ShoppingCart::getCart(), $nameOnCard, $cardNumber);
		if (!$orderId) {
			$this->forwardRequest(['Could not place order!']);
			return false;
		}

		ShoppingCart::clear();
		Util::redirect('index.php?view=success&orderId=' . rawurlencode($orderId));

		return true;
	}

	protected function forwardRequest(array $errors = null, $target = null) {
		if ($target == null) {
			if (!isset($_REQUEST[self::PAGE])) {
				throw new Exception('Missing target!');
			}
			else {
				$target = $_REQUEST[self::PAGE];
			}
		}

		if (sizeof($errors) > 0) {
			$target .= "&errors=" . urlencode(serialize($errors));
		}

		header('Location:' . $target);
		exit();
	}



}