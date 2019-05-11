<?php
/**
 * Created by PhpStorm.
 * User: r8r
 * Date: 2019-05-11
 * Time: 10:23
 */

namespace Bookshop;


class Entity extends BaseObject {

	private $id;

	public function getId() {
		return $this->id;
	}

	public function __construct(int $id) {
		$this->id = intval($id);
	}

}