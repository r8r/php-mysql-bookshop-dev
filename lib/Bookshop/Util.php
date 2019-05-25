<?php
/**
 * Created by PhpStorm.
 * User: r8r
 * Date: 2019-05-11
 * Time: 10:58
 */

namespace Bookshop;


class Util extends BaseObject {

	public static function escape(string $string) : string {
		return nl2br(htmlentities($string));
	}

	public static function action(string $action, array $params = null) : string {
		$page = isset($_REQUEST[Controller::PAGE]) ?
			$_REQUEST[Controller::PAGE] :
			$_SERVER['REQUEST_URI'];

		$res = "index.php?" . Controller::ACTION . "=" . $action . "&" . Controller::PAGE . "=" . rawurlencode($page);

		if (is_array($params)) {
			foreach ($params AS $name => $value) {
				$res .= "&" . rawurlencode($name) . "=" . rawurlencode($value);
			}
		}

		return $res;
	}

	public static function redirect(string $page = null) {
		if ($page == null) {
			$page = isset($_REQUEST[Controller::PAGE]) ?
				$_REQUEST[Controller::PAGE] :
				$_SERVER['REQUEST_URI'];
		}

		header("Location:" . $page);
		exit();
	}

}