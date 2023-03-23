<?php

namespace Bookshop;

class Util extends BaseObject {

	public static function escape(string $string) : string {
		return nl2br(htmlentities($string));
	}

	public static function action(string $action, array $params = null) {

		$page = (isset($_REQUEST[Controller::PAGE]) ? $_REQUEST[Controller::PAGE] : $_SERVER['REQUEST_URI']);

		$url = 'index.php?' . Controller::ACTION . '=' . rawurlencode($action) . '&amp;' . Controller::PAGE . '=' . rawurlencode($page);

		if (is_array($params)) {
			foreach ($params AS $name => $value) {
				$url .= '&amp;' . rawurlencode($name) . '=' . rawurlencode($value);
			}
		}

		return $url;
	}

	public static function redirect(string $page = null) {
		if ($page === null) {
			$page = (isset($_REQUEST[Controller::PAGE]) ? $_REQUEST[Controller::PAGE] : $_SERVER['REQUEST_URI']);
		}
		header('Location:' . $page);
		exit();
	}

}