<?php
/**
 * Created by PhpStorm.
 * User: r8r
 * Date: 2020-03-28
 * Time: 15:48
 */

namespace Bookshop;
use Data\DataManager;

class AuthenticationManager extends BaseObject {

	public static function authenticate(string $userName, string $password) : bool {
		$user = DataManager::getUserByUserName($userName);
		if ($user != null &&
		    $user->getPasswordHash() == hash('sha1', $userName . '|' . $password)
		) {
			$_SESSION['user'] = $user->getId();
			return true;
		}
		self::signOut();
		return false;
	}

	public static function signOut() {
		unset($_SESSION['user']);
	}

	public static function isAuthenticated() : bool {
		return isset($_SESSION['user']);
	}

	public static function getAuthenticatedUser() {
		return self::isAuthenticated() ? DataManager::getUserById($_SESSION['user']) : null;
	}
}