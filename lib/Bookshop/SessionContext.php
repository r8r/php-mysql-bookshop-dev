<?php
/**
 * Created by PhpStorm.
 * User: r8r
 * Date: 2020-03-28
 * Time: 14:04
 */

namespace Bookshop;


class SessionContext extends BaseObject {

	private static $exists = false;

	public static function create() : bool {
		if (!self::$exists) {
			self::$exists = session_start();
		}
		return self::$exists;
	}
}