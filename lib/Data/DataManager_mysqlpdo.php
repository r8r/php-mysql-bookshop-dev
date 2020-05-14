<?php

namespace Data;

use Bookshop\Category;
use Bookshop\User;
use Bookshop\Book;


/**
 * DataManager
 * PDO Version
 * 
 * 
 * @package    
 * @subpackage 
 * @author     John Doe <jd@fbi.gov>
 */
class DataManager implements IDataManager {

	private static $__connection;

	private static function getConnection() {

		if (!isset(self::$__connection)) {

			$type = 'mysql';
			$host = 'localhost';
			$name = 'fh_scm4_bookshop';
			$user = 'root';
			$pass = 'root';

			self::$__connection = new \PDO(
				$type . ':host=' . $host . '; dbname=' . $name . ';charset=utf8',
				$user,
				$pass
			);
		}

		return self::$__connection;
	}

	public static function exposeConnection() {
		return self::getConnection();
	}

	private static function closeConnection() {
		self::$__connection = null;
	}

	private static function close($cursor) {
		$cursor->closeCursor();
	}

	public static function query($connection, $query, $parameters = []) {
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

	public static function fetchObject($cursor) {
		return $cursor->fetchObject();
	}

	public static function getCategories() : array {
		$return = [];

		$con = self::getConnection();
		$res = self::query(
			$con,
			'SELECT id, name
			FROM categories;'
		);

		while ($cat = self::fetchObject($res)) {
			$return[] = new Category($cat->id, $cat->name);
		}
		self::close($res);
		self::closeConnection();

		return $return;
	}

	public static function lastInsertId($con) {
		return $con->lastInsertId();
	}

	public static function getBooksByCategory(int $categoryId) : array {
		$return = [];

		$con = self::getConnection();
		$res = self::query(
			$con,
			'SELECT id, categoryId, title, author, price
			FROM books
			WHERE categoryId = ?;',
			[$categoryId]
		);

		while ($book = self::fetchObject($res)) {
			$return[] = new Book($book->id, $book->categoryId, $book->title, $book->author, $book->price);
		}
		self::close($res);
		self::closeConnection();

		return $return;
	}

	public static function getUserById(int $userId) {
		$return = null;

		$con = self::getConnection();
		$res = self::query(
			$con,
			'SELECT id, userName, passwordHash
			FROM users
			WHERE id = ?
			LIMIT 1;',
			[$userId]
		);

		if ($user = self::fetchObject($res)) {
			$return = new User($user->id, $user->userName, $user->passwordHash);
		}
		self::close($res);
		self::closeConnection();

		return $return;
	}

	public static function getUserByUserName(string $userName) {
		$return = null;

		$con = self::getConnection();
		$res = self::query(
			$con,
			'SELECT id, userName, passwordHash
			FROM users
			WHERE userName = ?
			LIMIT 1;',
			[$userName]
		);

		if ($user = self::fetchObject($res)) {
			$return = new User($user->id, $user->userName, $user->passwordHash);
		}
		self::close($res);
		self::closeConnection();

		return $return;
	}

	public static function createOrder(int $userId, array $bookIds, string $nameOnCard, string $cardNumber) : int {
		$return = null;
		$con = self::getConnection();
		$con->beginTransaction();
		try {

			self::query($con,
				'INSERT INTO orders (
						userId, creditCardNumber, creditCardHolder
					) VALUES (
						?, ?, ?
					);'
				, [$userId, $cardNumber, $nameOnCard]);

			$orderId = self::lastInsertId($con);

			foreach ($bookIds as $bookId) {

				self::query($con,
					'INSERT INTO orderedbooks (
						orderId, bookId 
					) VALUES (
						?, ?
					);'
					, [$orderId, $bookId]);

			}

			$con->commit();
			$return =  date('Y') . ($orderId + 1000000);
		}
		catch (\Exception $e) {
			$con->rollBack();
			$return = null;
		}
		self::closeConnection();
		return $return;
	}

}