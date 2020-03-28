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

}