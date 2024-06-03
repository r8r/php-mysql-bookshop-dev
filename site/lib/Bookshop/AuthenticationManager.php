<?php

namespace Bookshop;

use Data\DataManager;

SessionContext::create();

class AuthenticationManager extends BaseObject {

	public static function authenticate(string $userName, string $password) : bool {
		$user = DataManager::getUserByUserName($userName);
		if ($user != null && $user->getPasswordHash() == hash('sha1', $userName . '|' . $password)) {
			$_SESSION['user'] = $user->getId();
			return true;
		}
		self::signOut();
		return false;
	}

	public static function signOut() : void {
		unset($_SESSION['user']);
	}

	public static function inAuthenticated() : bool {
		return isset($_SESSION['user']);
	}

	public static function getAuthenticatedUser() : ?User {
		return self::inAuthenticated() ? DataManager::getUserById($_SESSION['user']) : null;
	}

}