<?php
/**
 * Created by PhpStorm.
 * User: r8r
 * Date: 2020-02-27
 * Time: 15:54
 */

namespace Bookshop;

class Book extends Entity {

	private $title;
	private $author;
	private $price;
	private $categoryId;

	public function __construct(int $id, int $categoryId, string $title, string $author, float $price) {
		parent::__construct($id);
		$this->title = $title;
		$this->author = $author;
		$this->price = floatval($price);
		$this->categoryId = intval($categoryId);
	}

	public function getTitle() : string {
		return $this->title;
	}

	public function getAuthor() : string {
		return $this->author;
	}


	public function getPrice() : float {
		return $this->price;
	}

	public function getCategoryId() : int {
		return $this->categoryId;
	}

}