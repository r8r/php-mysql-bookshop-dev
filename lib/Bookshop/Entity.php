<?php


namespace Bookshop;


interface IData {
	public function getId() : int;
}


class Entity extends BaseObject implements IData {

	private $id;

	public function __construct(int $id) {
		$this->id = intval($id);
	}

	public function getId() : int {
		return $this->id;
	}
}