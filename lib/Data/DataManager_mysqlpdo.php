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
class DataManager implements IDataManager {

	private static $__connection;

	private static function getConnection() {
		if (!self::$__connection) {

			$type = 'mysql';
			$host = 'localhost';
			$name = 'fh_scm4_bookshop';
			$user = 'root';
			$pass = 'root';

			self::$__connection = new \PDO($type . ':host=' . $host . ';dbname=' . $name, $user, $pass);
		}
		return self::$__connection;
	}

	public static function exposeConnection() {
		return self::getConnection();
	}

	private static function query($connection, $query, $parameters = []) {
		$connection->setAttribute(\PDO::ATTR_ERRMODE, \PDO::ERRMODE_EXCEPTION);

		try {
			$statement = $connection->prepare($query);

			$i = 1;
			foreach ($parameters AS $param) {
				if (is_int($param)) {
					$statement->bindValue($i, $param, \PDO::PARAM_INT);
				}
				if (is_string($param)) {
					$statement->bindValue($i, $param, \PDO::PARAM_STR);
				}
				$i++;
			}

			$statement->execute();
		}
		catch (\Exception $e) {
			die($e->getMessage());
		}

		return $statement;
	}

	private static function fetchObject($cursor) {
		return $cursor->fetchObject();
	}

	private static function close($cursor) {
		$cursor->closeCursor();
	}

	private static function closeConnection() {
		self::$__connection = null;
	}

	private static function lastInsertId($connection) {
		return $connection->lastInsertId();
	}

	public static function getCategories() : array {
		$categories = [];

		$con = self::getConnection();
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


	public static function getBooksByCategory(int $categoryId) : array {
		$books = [];

		$con = self::getConnection();

		$result = self::query($con,
			"SELECT id, title, author, price
			FROM books
			WHERE categoryId = ?;",
			[$categoryId]
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
			WHERE userName = ?
			LIMIT 1;",
			[$userName]
		);

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
			WHERE id = ?
			LIMIT 1;",
			[$id]
		);

		if ($u = self::fetchObject($result)) {
			$user = new User($u->id, $u->userName, $u->passwordHash);
		}

		self::close($result);
		self::closeConnection();

		return $user;
	}

	public static function createOrder(int $userId, array $bookIds, string $nameOnCard, string $cardNumber) : int {
		$orderId = false;

		$con = self::getConnection();

		$con->beginTransaction();

		try {

			self::query($con, "
				INSERT INTO orders (
					userId,
					creditCardNumber,
					creditCardHolder
				) VALUES (
					?,
					?,
					?
				);
			", [
				$userId,
				$cardNumber,
				$nameOnCard,
			]);

			$orderId = self::lastInsertId($con);

			foreach ($bookIds AS $bookId) {
				self::query($con, "
					INSERT INTO orderedbooks (
						orderId,
						bookId
					) VALUES (
						?,
						?
					);
				", [
					$orderId,
					$bookId,
				]);
			}

			$con->commit();
		}
		catch(\Exception $e) {
			$con->rollBack();
			$orderId = false;
		}

		self::closeConnection();

		return $orderId;
	}

}