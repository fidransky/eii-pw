<?php

namespace App\Models\Match;

use App\Models\AbstractManager;
use \PDO;


/**
 * @author Pavel Fidransky [jsem@pavelfidransky.cz]
 */
class MatchManager extends AbstractManager {

	public function __construct()
	{
		parent::__construct('`match`');
	}

}
