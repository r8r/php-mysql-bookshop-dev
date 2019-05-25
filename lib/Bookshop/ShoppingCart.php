<?php
/**
 * Created by PhpStorm.
 * User: r8r
 * Date: 2019-05-25
 * Time: 08:32
 */

namespace Bookshop;

// $_SESSION['cart']


class ShoppingCart extends BaseObject {

	public static function getCart() : array {
		return isset($_SESSION['cart']) && is_array($_SESSION['cart']) ?
			$_SESSION['cart'] : [];
	}

	public static function contains(int $bookId) : bool {
		$cart = self::getCart();
		return array_key_exists($bookId, $cart);
	}


}