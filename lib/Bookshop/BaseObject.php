<?php
/**
 * Created by PhpStorm.
 * User: r8r
 * Date: 2019-05-11
 * Time: 10:28
 */

namespace Bookshop;


class BaseObject {

	public function __call(string $name, array $params) {
		throw new \Exception("Method " . $name . " does not exist.");
	}

	public static function __callStatic(string $name, array $params) {
		throw new \Exception("Static method " . $name . " does not exist.");
	}

	public function __get(string $name) {
		throw new \Exception("Attribute " . $name . " does not exist.");
	}

	public function __set(string $name, $value) {
		throw new \Exception("Attribute " . $name . " does not exist.");
	}

}