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

	public function save($data, $id = null)
	{
		$leagueId = $data['leagueId'];
		unset($data['leagueId']);

		$result = parent::save($data, $id);

		if ($result) {
			if ($id) {
				return true;

			} else {
				$query = 'INSERT INTO league_team(league_id, team_id) VALUES (:leagueId, :teamId)';
				$args = [
					':leagueId' => $leagueId,
					':teamId' => $result,
				];

				$this->database->query($query, $args);

				return $result;
			}
		}

		return false;
	}

	public function getLeague($teamId)
	{
		$query = 'SELECT league.* FROM league_team LEFT JOIN league ON league.id = league_team.league_id WHERE league_team.team_id = :teamId LIMIT 1';
		$args = [
			':teamId' => $teamId,
		];

		return $this->database
			->query($query, $args)
			->fetch(PDO::FETCH_ASSOC);		
	}

}
