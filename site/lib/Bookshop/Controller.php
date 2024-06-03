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
	public const CC_NAME = 'nameOnCard';
	public const CC_NUMBER = 'cardNumber';
	public const USER_NAME = 'userName';
	public const USER_PASSWORD = 'password';


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

			case self::ACTION_ORDER :
				$user = AuthenticationManager::getAuthenticatedUser();

				if ($user == null) {
					$this->forwardRequest(['Not logged in.']);
					break;
				}

				if (!$this->processCheckout($_POST[self::CC_NAME], $_POST[self::CC_NUMBER])) {
					$this->forwardRequest(['Checkout failed.']);
				}

				break;

			case self::ACTION_LOGIN :
				if (!AuthenticationManager::authenticate($_REQUEST[self::USER_NAME], $_REQUEST[self::USER_PASSWORD])) {
					$this->forwardRequest(['Invalid user name or invalid password']);
				}
				//Util::redirect();
				break;

			case self::ACTION_LOGOUT :
				AuthenticationManager::signOut();
				Util::redirect();
				break;

			default :
				throw new \Exception('Unknown controller action: ' . $action);
				break;
		}
	}

	protected function processCheckout(string $nameOnCard = null, string $cardNumber = null) : bool {

		$errors = [];

		$nameOnCard = trim($nameOnCard);
		if ($nameOnCard == null || strlen($nameOnCard) == 0) {
			$errors[] = 'Invalid name on card.';
		}
		if ($cardNumber == null || strlen($cardNumber) != 16 || !ctype_digit($cardNumber)) {
			$errors[] = 'Invalid card number. Card number must be sixteen digits.';
		}

		if (sizeof($errors) > 0) {
			$this->forwardRequest($errors);
			return false;
		}

		if (ShoppingCart::size() === 0) {
			$this->forwardRequest(['Shopping cart is empty.']);
			return false;
		}

		$user = AuthenticationManager::getAuthenticatedUser();
		$orderId = DataManager::createOrder($user->getId(), ShoppingCart::getAll(), $nameOnCard, $cardNumber);

		if (!$orderId) {
			$this->forwardRequest(['Could not create order.']);
			return false;
		}

		ShoppingCart::clear();
		Util::redirect('index.php?view=success&orderId=' . $orderId);

		return true;
	}

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

		//die($target);

		header('location: ' . $target);
		exit();
	}

}