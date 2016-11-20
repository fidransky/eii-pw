<?php

namespace App\Models\League;

use App\Models\AbstractManager;
use PDO;


/**
 * @author Pavel Fidransky [jsem@pavelfidransky.cz]
 */
class LeagueManager extends AbstractManager {

	public function __construct()
	{
		parent::__construct('league');
	}

}
