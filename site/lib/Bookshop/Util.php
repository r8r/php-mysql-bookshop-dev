<?php

namespace Bookshop;

class Util extends BaseObject {

	public static function escape(string $string) : string {
		return nl2br(htmlspecialchars($string));
	}

}