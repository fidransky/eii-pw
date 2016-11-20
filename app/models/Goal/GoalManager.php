<?php

namespace App\Models\Goal;

use App\Models\AbstractManager;
use App\Models\Player\PlayerManager;
use DateInterval;
use PDO;


/**
 * @author Pavel Fidransky [jsem@pavelfidransky.cz]
 */
class GoalManager extends AbstractManager {

	public static $types = ['regular', 'penalty', 'straight kick'];


	public function __construct()
	{
		parent::__construct('goal');

		$this->playerManager = new PlayerManager;
	}

	public function getFromMatch($matchId, $teamId = null)
	{
		$query = 'SELECT * FROM ' . $this->tableName . ' WHERE match_id = :matchId';
		$args = [
			':matchId' => $matchId,
		];

		if ($teamId) {
			$query .= ' AND team_id = :teamId';
			$args[':teamId'] = $teamId;
		}

		return $this->database
			->query($query, $args)
			->fetchAll(PDO::FETCH_ASSOC);
	}

	public function process($goal)
	{
		$goal['time__raw'] = $goal['time'];
		list($hours, $minutes, $seconds) = explode(':', $goal['time__raw']);
		$goal['time'] = new DateInterval('PT' . $hours . 'H'. $minutes . 'M' . $seconds . 'S');

		$goal['type__raw'] = (int) $goal['type'];
		$goal['type'] = self::$types[$goal['type__raw']];

		$goal['player'] = $this->playerManager->get($goal['player_id']);

		return $goal;
	}

}
