<?php
/**
 * Created by PhpStorm.
 * User: r8r
 * Date: 2020-02-27
 * Time: 15:54
 */

namespace Bookshop;

class User extends Entity {

	private $userName;
	private $passwordHash;

	public function __construct(int $id, string $userName, string $passwordHash) {
		parent::__construct($id);
		$this->userName = $userName;
		$this->passwordHash = $passwordHash;
	}

	public function getUserName() : string {
		return $this->userName;
	}

	public function getPasswordHash() : string {
		return $this->passwordHash;
	}

}