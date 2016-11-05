<?php

namespace App\Models\Player;

use App\Models\AbstractManager;
use \PDO;


/**
 * @author Pavel Fidransky [jsem@pavelfidransky.cz]
 */
class PlayerManager extends AbstractManager {

	public function __construct()
	{
		parent::__construct('player');
	}

}
