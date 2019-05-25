<?php
/**
 * Created by PhpStorm.
 * User: r8r
 * Date: 2019-05-25
 * Time: 10:11
 */

namespace Bookshop;

use Data\DataManager;

SessionContext::create();

class AuthenticationManager extends BaseObject {

	public static function authenticate(string $userName, string $password) : bool {
		$user = DataManager::getUserByUsername($userName);

		if ($user != null && $user->getPasswordHash() === hash('sha1', $password)) {
			$_SESSION['user'] = $user->getId();
			return true;
		}

		return false;
	}

	public static function isAuthenticated() : bool {
		return isset($_SESSION['user']);
	}

	public static function signOut() {
		unset($_SESSION['user']);
	}

	public static function getAuthenticatedUser() {
		return self::isAuthenticated() ? DataManager::getUserById($_SESSION['user']) : null;
	}

}