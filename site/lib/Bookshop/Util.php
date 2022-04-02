<?php

namespace Bookshop;

class Util extends BaseObject {

	public static function escape(string $string) : string {
		return nl2br(htmlspecialchars($string));
	}

	public static function action(string $action, array $params = null) : string {
		// index.php?action=addToCart&page=/index.php?...&categoryId=3

		$page = isset($_REQUEST[Controller::PAGE]) ?
			$_REQUEST[Controller::PAGE] :
			$_SERVER['REQUEST_URI'];

		$url = 'index.php?' .
		       Controller::ACTION . '=' . rawurlencode($action) . '&amp;' .
		       Controller::PAGE . '=' . rawurlencode($page);

		if (is_array($params)) {
			foreach ($params as $key => $value) {
				$url .= '&amp;' . rawurlencode($key) . '=' . rawurlencode($value);
			}
		}

		return $url;
	}

	public static function redirect(string $page = null) {
		if ($page == null) {
			$page = isset($_REQUEST[Controller::PAGE]) ?
				$_REQUEST[Controller::PAGE] :
				$_SERVER['REQUEST_URI'];
		}

		header('Location:' . $page);
	}
}