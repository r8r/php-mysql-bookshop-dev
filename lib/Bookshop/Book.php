<?php


namespace Bookshop;


class Book extends Entity {

	private $categoryId;
	private $title;
	private $author;
	private $price;

	public function __construct(int $id, int $categoryId, string $title, string $author, float $price) {
		parent::__construct($id);
		$this->categoryId = intval($categoryId);
		$this->title = $title;
		$this->author = $author;
		$this->price = floatval($price);
	}

	public function getCategoryId() : int {
		return $this->categoryId;
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

}