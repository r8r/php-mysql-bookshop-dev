<?php

namespace Bookshop;

class Util extends BaseObject {

	public static function escape(string $string) : string {
		return nl2br(htmlentities($string));
	}

	public static function action(string $action, array $params = null) : string {


		// action
		$res = 'index.php?' . Controller::ACTION . '=' . $action;

		// page

		/* // langschreibweise
		if (isset($_REQUEST[Controller::PAGE])) {
			$page = $_REQUEST[Controller::PAGE];
		}
		else {
			$page = $_SERVER['REQUEST_URI'];
		}
		*/

		/* // nicht gleichwertig, aber ähnlich: coalesce Operator in PHP 8
		$page = $_REQUEST[Controller::PAGE] ??
			$_SERVER['REQUEST_URI'];
		*/

		$page = isset($_REQUEST[Controller::PAGE]) ?
			$_REQUEST[Controller::PAGE] :
			$_SERVER['REQUEST_URI'];

		$res .= htmlspecialchars('&') . rawurlencode(Controller::PAGE) . '=' . rawurlencode($page);

		// params
		if (isset($params)) {
			foreach ($params as $key => $value) {
				$res .= htmlspecialchars('&') . rawurlencode($key) . '=' . rawurlencode($value);
			}
		}

		return $res;
	}

	/**
	 * redirect mit optionaler url - HINWEIS - redirection attack möglich!
	 *
	 * @param string $page  uri optional
	 */
	public static function redirect(string $page = null) : never {
		if ($page == null) {
			$page = isset($_REQUEST[Controller::PAGE]) ?
				$_REQUEST[Controller::PAGE] :
				$_SERVER['REQUEST_URI'];
		}
		header("Location: $page");
		exit();
	}
}