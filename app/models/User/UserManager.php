<?php

namespace App\Models\User;

use App\Models\AbstractManager;
use \PDO;


/**
 * @author Pavel Fidransky [jsem@pavelfidransky.cz]
 */
class UserManager extends AbstractManager {

	public function __construct()
	{
		parent::__construct('user');
	}

	public function getByMail($mail)
	{
		$query = 'SELECT * FROM ' . $this->tableName . ' WHERE mail = :mail LIMIT 1';
		$args = [
			':mail' => $mail,
		];

		return $this->database
			->query($query, $args)
			->fetch(PDO::FETCH_ASSOC);
	}

}
