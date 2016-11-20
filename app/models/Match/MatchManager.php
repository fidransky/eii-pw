<?php

namespace App\Models\Match;

use App\Models\AbstractManager;
use App\Models\Team\TeamManager;
use App\Models\Goal\GoalManager;
use DateTime;
use PDO;


/**
 * @author Pavel Fidransky [jsem@pavelfidransky.cz]
 */
class MatchManager extends AbstractManager {

	public static $states = ['created', 'ongoing', 'finished'];

	private $teamManager;
	private $goalManager;


	public function __construct()
	{
		parent::__construct('`match`');

		$this->teamManager = new TeamManager;
		$this->goalManager = new GoalManager;
	}

	public function getCreated()
	{
		return $this->database
			->query('SELECT * FROM ' . $this->tableName . ' WHERE state=0')
			->fetchAll();
	}

	public function getOngoing()
	{
		return $this->database
			->query('SELECT * FROM ' . $this->tableName . ' WHERE state=1')
			->fetchAll();
	}

	public function getFinished()
	{
		return $this->database
			->query('SELECT * FROM ' . $this->tableName . ' WHERE state=2')
			->fetchAll();
	}

	public function saveWithPlayers($data, $players, $id = null)
	{
		try {
			$this->database->startTransaction();

			// save player
			$result = $this->save($data, $id);

			if ($result) {
				$insertQuery = 'INSERT INTO lineup(match_id, player_id) VALUES (:matchId, :playerId)';
				$deleteQuery = 'DELETE FROM lineup WHERE match_id = :matchId AND player_id = :playerId';

				if ($id) {
					$originalPlayers = array_map(function($player) {
						return $player['id'];
					}, $this->getPlayers($id));

					$added = array_diff($players, $originalPlayers);
					$removed = array_diff($originalPlayers, $players);

				} else {
					$id = $result;
					$added = $players;
					$removed = [];
				}

				// save leagues
				foreach ($added as $playerId) {
					$args = [
						':matchId' => $id,
						':playerId' => $playerId,
					];

					$this->database->query($insertQuery, $args);
				}

				foreach ($removed as $teamId) {
					$args = [
						':matchId' => $id,
						':playerId' => $playerId,
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

	public function getPlayers($matchId, $teamId = null)
	{
		$query = 'SELECT player.* FROM lineup LEFT JOIN player ON player.id = lineup.player_id WHERE lineup.match_id = :matchId';
		$args = [
			':matchId' => $matchId,
		];

		if ($teamId) {
			/* TODO how do I do that? add team_id column to lineup table?
			$query .= ' AND player.team_id = :teamId';
			$args[':teamId'] = $teamId;
			*/
		}

		return $this->database
			->query($query, $args)
			->fetchAll(PDO::FETCH_ASSOC);
	}

	public function getGoals($matchId, $teamId = null)
	{
		return $this->goalManager->getFromMatch($matchId, $teamId);
	}

	public function setStarted($matchId, $started = 'now')
	{
		$match = $this->get($matchId);

		if ($started === 'now') {
			$now = new DateTime;
			$started = $now->format(AbstractManager::$datetimeFormat);

			$match['part'] += 1;
		}

		$match['state'] = 1;
		$match['started'] = $started;

		return $this->save($match, $matchId);
	}

	public function setEnded($matchId)
	{
		$match = $this->get($matchId);

		$homeTeamGoals = $this->getGoals($match['id'], $match['home_team_id']);
		$visitingTeamGoals = $this->getGoals($match['id'], $match['visiting_team_id']);

		$match['state'] = 2;
		$match['started'] = null;
		$match['home_team_points'] = $homeTeamGoals == $visitingteamgoals ? 1 : ($homeTeamGoals > $visitingTeamGoals ? 3 : 0);
		$match['visiting_team_points'] = $homeTeamGoals == $visitingTeamGoals ? 1 : ($homeTeamGoals > $visitingTeamGoals ? 0 : 3);

		return $this->save($match, $matchId);
	}

	public function process($match, $processNested = false)
	{
		$match['homeTeam'] = $this->teamManager->get($match['home_team_id']);
		$match['visitingTeam'] = $this->teamManager->get($match['visiting_team_id']);

		$match['homeTeam']['goals'] = $this->getGoals($match['id'], $match['home_team_id']);
		$match['visitingTeam']['goals'] = $this->getGoals($match['id'], $match['visiting_team_id']);
		if ($processNested) {
			$match['homeTeam']['goals'] = array_map([$this->goalManager, 'process'], $match['homeTeam']['goals']);
			$match['visitingTeam']['goals'] = array_map([$this->goalManager, 'process'], $match['visitingTeam']['goals']);
		}

		$match['date__raw'] = $match['date'];
		$match['date'] = new DateTime($match['date__raw']);

		$match['state__raw'] = (int) $match['state'];
		$match['state'] = self::$states[$match['state__raw']];

		$match['started__raw'] = $match['started'];
		if ($match['started__raw'] !== null) {
			$match['started'] = new DateTime($match['started__raw']);

			$now = new DateTime;
			$match['time'] = $now->diff(new DateTime($match['started__raw']));
		} else {
			$match['time'] = null;
		}

		return $match;
	}

}
