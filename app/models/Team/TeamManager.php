<?php

namespace App\Models\Team;

use App\Models\AbstractManager;
use \PDO;


/**
 * @author Pavel Fidransky [jsem@pavelfidransky.cz]
 */
class TeamManager extends AbstractManager {

	public function __construct()
	{
		parent::__construct('team');
	}

}
