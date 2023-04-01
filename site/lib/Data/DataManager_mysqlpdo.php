<?php

namespace Data;

use Bookshop\Category;
use Bookshop\User;
use Bookshop\Book;
use Bookshop\PagingResult;


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
			// create db connection

			$type = 'mysql';
			$host = 'db';
			$name = 'db';
			$user = 'db';
			$pass = 'db';

			self::$__connection = new \PDO($type . ':host=' . $host . ';dbname=' . $name . ';charset=utf8', $user, $pass);
		}
		return self::$__connection;
	}

	public static function exposeConnection() {
		return self::getConnection();
	}

	public static function query(\PDO $connection, string $query, array $params = []) : \PDOStatement {
		$connection->setAttribute(\PDO::ATTR_ERRMODE, \PDO::ERRMODE_EXCEPTION);

		try {

			$statement = $connection->prepare($query);

			$i = 1;
			foreach ($params AS $param) {
				if (is_int($param)) {
					$statement->bindValue($i, $param, \PDO::PARAM_INT);
				}
				if (is_string($param)) {
					$statement->bindValue($i, $param, \PDO::PARAM_STR);
				}
				$i++;
			}

			$statement->execute();

		} catch (\Exception $e) {
			die($e->getMessage());
		}

		return $statement;
	}

	public static function fetchObject($cursor) {
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

  /**
   * get the categories
   * 
   * @return array of Category-items
   */
  public static function getCategories() : array {
    $categories = [];

		$con = self::getConnection();
		$res = self::query($con,
			"SELECT id, name
			FROM categories"
		);

		while ($cat = self::fetchObject($res)) {
			$categories[] = new Category($cat->id, $cat->name);
		}

		self::close($res);
		self::closeConnection();

    return $categories;
  }

  /**
   * get the books per category
   * 
   * note: see how prepared statements replace "?" with array element values
   *
   * @param integer $categoryId  numeric id of the category
   * @return array of Book-items
   */
  public static function getBooksByCategory($categoryId)  : array {
	  $books = [];

	  $con = self::getConnection();
	  $res = self::query($con,
		  "SELECT id, categoryId, title, author, price
			FROM books
			WHERE categoryId = ?", [$categoryId]
	  );

	  while ($book = self::fetchObject($res)) {
		  $books[] = new Book($book->id, $book->categoryId, $book->title, $book->author, $book->price);
	  }

	  self::close($res);
	  self::closeConnection();

    return $books;
  }

  /**
   * get the User item by id
   * 
   * @param integer $userId  uid of that user
   * @return User | false
   */
  public static function getUserById($userId) : ?User {
    $user = null;

	  $con = self::getConnection();
	  $res = self::query($con,
		  "SELECT id, userName, passwordHash
			FROM users
			WHERE id = ?", [$userId]
	  );

	  if ($user = self::fetchObject($res)) {
		  $user = new User($user->id, $user->userName, $user->passwordHash);
	  }

	  self::close($res);
	  self::closeConnection();

    return $user;
  }

  /**
   * get the User item by name
   * 
   * @param string $userName  name of that user - must be exact match
   * @return User | false
   */
  public static function getUserByUserName($userName) :?User {
    $user = null;

	  $con = self::getConnection();
	  $res = self::query($con,
		  "SELECT id, userName, passwordHash
			FROM users
			WHERE userName = ?", [$userName]
	  );

	  if ($user = self::fetchObject($res)) {
		  $user = new User($user->id, $user->userName, $user->passwordHash);
	  }

	  self::close($res);
	  self::closeConnection();


    return $user;
  }

  /**
   * place to order with the shopping cart items
   * 
   * note: wrapped in a transaction
   *
   * @param integer $userId   id of the ordering user
   * @param array $bookIds    integers of book ids
   * @param string $nameOnCard  cc name
   * @param string $cardNumber  cc number
   * @return integer
   */
  public static function createOrder($userId, array $bookIds, $nameOnCard, $cardNumber) : int {
		$orderId = 0;

	  $con = self::getConnection();
		$con->beginTransaction();

		try {

			self::query($con, "
			INSERT INTO orders (
			  userId,
			  creditCardNumber,
				creditCardHolder
			) VALUES (
				?, ?, ?
			);", [$userId, $cardNumber, $nameOnCard]);

			$orderId = self::lastInsertId($con);

			foreach ($bookIds AS $bookId) {
				self::query($con, "
					INSERT INTO orderedbooks (
					  orderId,
					  bookId
					) VALUES (
						?, ?
					);", [$orderId, $bookId]);
			}

			$con->commit();
		}
		catch (\Exception $e) {
			$con->rollBack();
			$orderId = null;
		}

	  self::closeConnection();
    return $orderId;
  }

}
