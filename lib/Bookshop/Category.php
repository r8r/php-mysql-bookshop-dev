<?php


namespace Bookshop;


class Category extends Entity {

	private $name;

	public function __construct(int $id, string $name) {
		parent::__construct($id);
		$this->name = $name;
	}

	/*  ab php 8 möglich
	public function __construct(private int $id, private string $name) {

	}
	*/

	public function getName() : string {
		return $this->name;
	}

}