<?php
/**
 * Created by PhpStorm.
 * User: r8r
 * Date: 2019-05-25
 * Time: 08:32
 */

namespace Bookshop;

SessionContext::create();

class ShoppingCart extends BaseObject {

	public static function getCart() : array {
		return isset($_SESSION['cart']) && is_array($_SESSION['cart']) ?
			$_SESSION['cart'] : [];
	}

	public static function contains(int $bookId) : bool {
		$cart = self::getCart();
		return array_key_exists($bookId, $cart);
	}

	public static function add(int $bookId) {
		$cart = self::getCart();
		$cart[$bookId] = $bookId;
		self::storeCart($cart);
	}

	public static function remove(int $bookId) {
		$cart = self::getCart();
		unset($cart[$bookId]);
		self::storeCart($cart);
	}

	public static function storeCart(array $cart) {
		$_SESSION['cart'] = $cart;
	}

}