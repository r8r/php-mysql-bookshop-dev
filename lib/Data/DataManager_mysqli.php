<?php

namespace Data;

use Bookshop\Category;
use Bookshop\Book;
use Bookshop\User;

//use Bookshop\PagingResult;

/**
 * DataManager
 * Mock Version
 *
 *
 * @package
 * @subpackage
 * @author     John Doe <jd@fbi.gov>
 */
class DataManager
	implements IDataManager {

	private static $__connection;

	private static function getConnection() {
		if ( ! self::$__connection) {

			$type = 'mysql';
			$host = 'localhost';
			$name = 'fh_scm4_bookshop';
			$user = 'root';
			$pass = 'root';

			self::$__connection = new \mysqli($host, $user, $pass, $name);

			if (mysqli_connect_errno()) {
				die('Unable to connect to database.');
			}
		}

		return self::$__connection;
	}

	public static function exposeConnection() {
		return self::getConnection();
	}

	private static function query($connection, $query) {
		$res = $connection->query($query);
		if ( ! $res) {
			die('Error in query "' . $query . '": ' . $connection->error);
		}

		return $res;
	}

	private static function fetchObject($cursor) {
		return $cursor->fetch_object();
	}

	private static function close($cursor) {
		$cursor->close();
	}

	private static function closeConnection() {
		self::$__connection = null;
	}

	private static function lastInsertId($connection) {
		return mysqli_insert_id($connection);
	}

	public static function getCategories(): array {
		$categories = [];

		$con    = self::getConnection();
		$result = self::query($con,
			"SELECT id, name
			FROM categories;"
		);

		while ($cat = self::fetchObject($result)) {
			$categories[] = new Category($cat->id, $cat->name);
		}

		self::close($result);
		self::closeConnection();

		return $categories;
	}


	public static function getBooksByCategory(int $categoryId): array {
		$books = [];

		$con = self::getConnection();

		$result = self::query($con,
			"SELECT id, title, author, price
			FROM books
			WHERE categoryId = " . intval($categoryId) . ";"
		);

		while ($book = self::fetchObject($result)) {
			$books[] = new Book($book->id, $categoryId, $book->title, $book->author, $book->price);
		}

		self::close($result);
		self::closeConnection();

		return $books;
	}

	public static function getUserByUsername(string $userName) {
		$user = false;

		$con = self::getConnection();

		$result = self::query($con,
			"SELECT id, userName, passwordHash
			FROM users
			WHERE userName = \"" . $con->real_escape_string($userName) . "\"
			LIMIT 1;");

		if ($u = self::fetchObject($result)) {
			$user = new User($u->id, $u->userName, $u->passwordHash);
		}

		self::close($result);
		self::closeConnection();

		return $user;
	}

	public static function getUserById(int $id) {
		$user = false;

		$con = self::getConnection();

		$result = self::query($con,
			"SELECT id, userName, passwordHash
			FROM users
			WHERE id = " . intval($id) . "
			LIMIT 1;");

		if ($u = self::fetchObject($result)) {
			$user = new User($u->id, $u->userName, $u->passwordHash);
		}

		self::close($result);
		self::closeConnection();

		return $user;
	}

	public static function createOrder(int $userId, array $bookIds, string $nameOnCard, string $cardNumber): int {
		$orderId = false;

		$con = self::getConnection();

		self::query($con, "BEGIN;");

		$userId = intval($userId);
		$nameOnCard = $con->real_escape_string($nameOnCard);
		$cardNumber = $con->real_escape_string($cardNumber);

		self::query($con, "
				INSERT INTO orders (
					userId,
					creditCardNumber,
					creditCardHolder
				) VALUES (
					" . $userId . ",
					\"" . $cardNumber . "\",
					\"" . $nameOnCard . "\"
				);"
				);

		$orderId = intval(self::lastInsertId($con));

		foreach ($bookIds AS $bookId) {
			self::query($con, "
					INSERT INTO orderedbooks (
						orderId,
						bookId
					) VALUES (
						" . $orderId . ",
						" . intval($bookId) . "			
					);
				");
		}

		self::query($con, "COMMIT;");
		self::closeConnection();

		return $orderId;
	}

}