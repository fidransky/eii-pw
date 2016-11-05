<?php

namespace App\Models;

use \PDO;


/**
 * @author Pavel Fidransky [jsem@pavelfidransky.cz]
 */
abstract class AbstractManager implements IAbstractManager {

	protected $database;
	protected $tableName;


	public function __construct($tableName)
	{
		$this->database = new Database;
		$this->tableName = $tableName;
	}

	public function getAll()
	{
		return $this->database
			->query('SELECT * FROM ' . $this->tableName)
			->fetchAll();
	}

	public function countAll()
	{
		$query = 'SELECT COUNT(*) FROM ' . $this->tableName;

		return $this->database
			->query($query)
			->fetchColumn();
	}

	public function get($id)
	{
		$query = 'SELECT * FROM ' . $this->tableName . ' WHERE id = :id LIMIT 1';
		$args = [
			':id' => $id,
		];

		return $this->database
			->query($query, $args)
			->fetch(PDO::FETCH_ASSOC);
	}

	public function exists($id)
	{
		return $this->get($id) !== null;
	}

	public function save($data, $id = null)
	{
		if ($id) {
			$query = 'UPDATE ' . $this->tableName . ' SET ';
			$args = [];

			$set = [];
			foreach ($data as $index => $value) {
				$set[] = $index . ' = :' . $index;
				$args[':' . $index] = $value;
			}

			$query .= implode(', ', $set);
			$query .= ' WHERE id = :id';
			$args[':id'] = $id;

			return $this->database->query($query, $args);

		} else {
			$query = 'INSERT INTO ' . $this->tableName;
			$args = [];

			$indexes = [];
			$set = [];
			foreach ($data as $index => $value) {
				$indexes[] = $index;
				$set[] = ':' . $index;
				$args[':' . $index] = $value;
			}

			$query .= '(' . implode(', ', $indexes) . ')';
			$query .= ' VALUES ';
			$query .= '(' . implode(', ', $set) . ')';

			$this->database->query($query, $args);

			return $this->database->getLastInsertId();
		}
	}

	public function remove($id)
	{
		$query = 'DELETE FROM ' . $this->tableName . ' WHERE id = :id';
		$args = [
			':id' => $id,
		];

		return $this->database
			->query($query, $args);
	}
	
}
