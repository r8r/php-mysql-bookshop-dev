<?php
/**
 * Created by PhpStorm.
 * User: r8r
 * Date: 2020-03-28
 * Time: 13:38
 */

namespace Bookshop;


class Util extends BaseObject {

	public static function escape(string $string) : string {
		return nl2br(htmlentities($string));

		// &  &amp;
		// "  &quot;
		// <  &lt;
		// >  &gt;
		//    &nbsp;
	}


	/**
	 * redirect mit optionaler url - HINWEIS - redirection attack möglich!
	 *
	 * @param string $page  uri optional
	 * @return null
	 */
	public static function redirect(string $page = null) {
		if ($page == null) {
			$page = isset($_REQUEST[Controller::PAGE]) ?
				$_REQUEST[Controller::PAGE] :
				$_SERVER['REQUEST_URI'];
		}
		header("Location: $page");
		exit();
	}

	/**
	 * GET parameter "page" adds current page to action so that a redirect
	 * back to this page is possible after successful execution of POST action
	 * if "page" has been set before then just keep the current value (to avoid
	 * problem with "growing URLs" when a POST form is rendered "a second time"
	 * e.g. during a forward after an unsuccessful POS action)
	 *
	 * Be sure to check for invalid / insecure page redirects!!
	 *
	 * @param string $action  uri optional
	 * @param array $params  array key/value pairs
	 * @return string
	 */
	public static function action(string $action, array $params = null) : string {
		$page = isset($_REQUEST[Controller::PAGE]) ?
			$_REQUEST[Controller::PAGE] :
			$_SERVER['REQUEST_URI'];

		$res = 'index.php?' . Controller::ACTION . '=' . rawurlencode($action) . '&' . Controller::PAGE . '=' .
		       rawurlencode($page);

		if (is_array($params)) {
			foreach ($params as $name => $value) {
				$res .= '&' . rawurlencode($name) . '=' . rawurlencode($value);
			}
		}

		return $res;
	}

}