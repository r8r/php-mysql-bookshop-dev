<?php
/**
 * Created by PhpStorm.
 * User: r8r
 * Date: 2020-02-27
 * Time: 15:54
 */

namespace Bookshop;

class Category extends Entity {

	private $name;

	public function __construct(int $id, string $name) {
		parent::__construct($id);
		$this->name = $name;
	}

	public function getName() : string {
		return $this->name;
	}
}