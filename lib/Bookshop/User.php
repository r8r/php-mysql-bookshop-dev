<?php
/**
 * Created by PhpStorm.
 * User: r8r
 * Date: 2019-05-25
 * Time: 10:11
 */

namespace Bookshop;

class User extends entity {
	private $userName;
	private $passwordHash;

	public function getUserName() : string {
		return $this->userName;
	}

	public function getPasswordHash() : string {
		return $this->passwordHash;
	}

	public function __construct(int $id, string $userName, string $passwordHash) {
		parent::__construct($id);
		$this->userName = $userName;
		$this->passwordHash = $passwordHash;
	}
}