<?php

namespace App\Models;

use \PDO;
use \PDOException;


class Database {

	const SQL_HOST = 'localhost';
	const SQL_DBNAME = 'eii_pw';
	const SQL_USERNAME = 'admin';
	const SQL_PASSWORD = 'admin';

	private $connection;


	public function __construct($prefix = null, $debug = false)
	{
		$dsn = 'mysql:dbname=' . self::SQL_DBNAME . ';host=' . self::SQL_HOST . ';charset=utf8';
		$user = self::SQL_USERNAME;
		$password = self::SQL_PASSWORD;

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
	
}
