<?php
/**
 * Created by PhpStorm.
 * User: r8r
 * Date: 2019-05-11
 * Time: 10:06
 */

namespace Bookshop;

class Category extends Entity {

	private $name;

	public function getName() : string {
		return $this->name;
	}

	public function __construct(int $id, string $name) {
		parent::__construct($id);
		$this->name = $name;
	}

}