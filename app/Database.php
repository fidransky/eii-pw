<?php

namespace App;

use \PDO;
use \PDOException;


/**
 * @author Pavel Fidransky [jsem@pavelfidransky.cz]
 */
class Database {

	/** @var \PDO */
	private $connection;


	public function __construct($prefix = null, $debug = false)
	{
		$dsn = 'mysql:dbname=' . SQL_DBNAME . ';host=' . SQL_HOST . ';charset=utf8';
		$user = SQL_USERNAME;
		$password = SQL_PASSWORD;

		try {
			$this->connection = new PDO($dsn, $user, $password);
			$this->connection->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
		} catch (PDOException $e) {
			die('Connection failed: ' . $e->getMessage());
		}
	}

	public function query($query, $args = [])
	{
		$preparedQuery = $this->connection->prepare($query);
		$preparedQuery->execute($args);

		return $preparedQuery;
	}

	public function getLastInsertId()
	{
		return $this->connection->lastInsertId();
	}

	public function startTransaction()
	{
		return $this->connection->beginTransaction();
	}

	public function commitTransaction()
	{
		return $this->connection->commit();
	}

	public function rollbackTransaction()
	{
		return $this->connection->rollBack();
	}
	
}
