<?php

namespace Bookshop;

class ShoppingCart extends BaseObject {
	//clear()
	//size()
	//getAll()

	public static function add(int $bookId) : void {
		$cart = self::getCart();
		$cart[$bookId] = $bookId;
		self::storeCart($cart);
	}

	public static function remove(int $bookId) : void {
		$cart = self::getCart();
		unset($cart[$bookId]);
		self::storeCart($cart);
	}

	public static function clear() : void {
		self::storeCart([]);
	}

	public static function contains(int $bookId) : bool {
		$cart = self::getCart();
		return isset($cart[$bookId]);
	}

	public static function size() : int {
		return sizeof(self::getCart());
	}

	public static function getAll() : array {
		return self::getCart();
	}

	private static function getCart() : array {
		return $_SESSION['cart'] ?? [];
	}

	private static function storeCart(array $cart) : void {
		$_SESSION['cart'] = $cart;
	}
}