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

	public function getOngoing()
	{
		return $this->database
			->query('SELECT * FROM ' . $this->tableName . ' WHERE state=1')
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

}
