<?php

namespace Bookshop;

class ShoppingCart extends BaseObject {

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

	public static function size() : int {
		return sizeof(self::getCart());
	}

	public static function contains(int $bookId) : bool {
		$cart = self::getCart();
		return array_key_exists($bookId, $cart);
	}

	public static function getAll() : array {
		return self::getCart();
	}

	public static function clearCart() : void {
		self::storeCart([]);
	}

	private static function getCart() : array {
		return $_SESSION['cart'] ?? [];
	}

	private static function storeCart(array $cart) {
		$_SESSION['cart'] = $cart;
	}

}