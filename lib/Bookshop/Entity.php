<?php
/**
 * Created by PhpStorm.
 * User: r8r
 * Date: 2020-02-27
 * Time: 15:54
 */

namespace Bookshop;

interface IEntity {
	public function getId() : int;
}

class Entity extends BaseObject implements IEntity {
	private $id;

	public function __construct(int $id) {
		$this->id = intval($id);
	}

	public function getId() : int {
		return $this->id;
	}
}