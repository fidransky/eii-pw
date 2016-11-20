<?php

namespace App\Models\Team;

use App\Models\AbstractManager;
use \PDO;
use \PDOException;


/**
 * @author Pavel Fidransky [jsem@pavelfidransky.cz]
 */
class TeamManager extends AbstractManager {

	public function __construct()
	{
		parent::__construct('team');
	}

	public function saveWithLeagues($data, $leagues, $id = null)
	{
		try {
			$this->database->startTransaction();

			// save team
			$result = $this->save($data, $id);

			if ($result) {
				$insertQuery = 'INSERT INTO league_team(league_id, team_id) VALUES (:leagueId, :teamId)';
				$deleteQuery = 'DELETE FROM league_team WHERE league_id = :leagueId AND team_id = :teamId';

				if ($id) {
					$originalLeagues = array_map(function($league) {
						return $league['id'];
					}, $this->getLeagues($id));

					$added = array_diff($leagues, $originalLeagues);
					$removed = array_diff($originalLeagues, $leagues);

				} else {
					$id = $result;
					$added = $leagues;
					$removed = [];
				}

				// save leagues
				foreach ($added as $leagueId) {
					$args = [
						':leagueId' => $leagueId,
						':teamId' => $id,
					];

					$this->database->query($insertQuery, $args);
				}

				foreach ($removed as $leagueId) {
					$args = [
						':leagueId' => $leagueId,
						':teamId' => $id,
					];

					$this->database->query($deleteQuery, $args);
				}
			}

			$this->database->commitTransaction();
			return $result;

		} catch (PDOException $e) {
			$this->database->rollbackTransaction();
			throw $e;
		}

		return false;
	}

	public function getLeagues($teamId)
	{
		$query = 'SELECT league.* FROM league_team LEFT JOIN league ON league.id = league_team.league_id WHERE league_team.team_id = :teamId';
		$args = [
			':teamId' => $teamId,
		];

		return $this->database
			->query($query, $args)
			->fetchAll(PDO::FETCH_ASSOC);
	}

	public function getPlayers($teamId)
	{
		$query = 'SELECT player.* FROM team_player LEFT JOIN player ON player.id = team_player.player_id WHERE team_player.team_id = :teamId';
		$args = [
			':teamId' => $teamId,
		];

		return $this->database
			->query($query, $args)
			->fetchAll(PDO::FETCH_ASSOC);
	}

}
