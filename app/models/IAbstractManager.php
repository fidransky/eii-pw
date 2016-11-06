<?php

namespace App\Models;


/**
 * Base interface of entity manager.
 *
 * Date: 09.06.2016
 *
 * @author Pavel Fidransky [jsem@pavelfidransky.cz]
 */
interface IAbstractManager
{
	/**
	 * Returns all records from the repository.
	 * @return mixed
	 */
	function getAll();

	/**
	 * Counts all rows in the repository.
	 * @return integer
	 */
	function countAll();

	/**
	 * Returns a single record from the repository with the specified identifier.
	 * @param $id
	 * @return mixed
	 */
	function get($id);

	/**
	 * Returns true if the record with the specified ID exists.
	 * @param $id
	 * @return mixed
	 */
	function exists($id);

	/**
	 * Saves the entity.
	 * @param $data
	 * @param $id
	 * @return mixed
	 */
	function save($data, $id = null);

	/**
	 * Removes the entity with the specified ID.
	 * @param $id
	 * @return mixed
	 */
	function remove($id);

}