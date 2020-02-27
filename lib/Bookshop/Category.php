<?php
/**
 * Created by PhpStorm.
 * User: r8r
 * Date: 2020-02-27
 * Time: 15:54
 */

namespace Bookshop;

class Category {

	private $id;
	private $name;

	public function __construct(int $id, string $name) {
		$this->id = intval($id);
		$this->name = $name;
	}

	public function getId() : int {
		return $this->id;
	}

	public function getName() : string {
		return $this->name;
	}

}