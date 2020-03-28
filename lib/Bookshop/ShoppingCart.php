<?php
/**
 * Created by PhpStorm.
 * User: r8r
 * Date: 2020-03-28
 * Time: 14:08
 */

namespace Bookshop;


class ShoppingCart extends BaseObject {

	private static function storeCart(array $cart) {
		$_SESSION['cart'] = $cart;
	}

	private static function getCart() : array {
		return $_SESSION['cart'] ?? [];
	}

	public static function getAll() : array {
		return self::getCart();
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

	public static function size() : int {
		return sizeof(self::getCart());
	}

	public static function contains(int $bookId) : bool {
		return array_key_exists($bookId, self::getCart());
	}

	public static function clear() {
		self::storeCart([]);
	}



}