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

	public function get($id)
	{
		return $this->resolvePost(parent::get($id));
	}

	public function saveWithTeams($data, $teams, $id = null)
	{
		try {
			$this->database->startTransaction();

			// save player
			$result = $this->save($data, $id);

			if ($result) {
				$insertQuery = 'INSERT INTO team_player(team_id, player_id) VALUES (:teamId, :playerId)';
				$deleteQuery = 'DELETE FROM team_player WHERE team_id = :teamId AND player_id = :playerId';

				if ($id) {
					$originalTeams = array_map(function($team) {
						return $team['id'];
					}, $this->getTeams($id));

					$added = array_diff($teams, $originalTeams);
					$removed = array_diff($originalTeams, $teams);

				} else {
					$id = $result;
					$added = $teams;
					$removed = [];
				}

				// save leagues
				foreach ($added as $teamId) {
					$args = [
						':teamId' => $teamId,
						':playerId' => $id,
					];

					$this->database->query($insertQuery, $args);
				}

				foreach ($removed as $teamId) {
					$args = [
						':teamId' => $teamId,
						':playerId' => $id,
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

	public function getTeams($playerId)
	{
		$query = 'SELECT team.* FROM team_player LEFT JOIN team ON team.id = team_player.team_id WHERE team_player.player_id = :playerId';
		$args = [
			':playerId' => $playerId,
		];

		return $this->database
			->query($query, $args)
			->fetchAll(PDO::FETCH_ASSOC);
	}

	private function resolvePost($player)
	{
		$player['post__raw'] = (int) $player['post'];

		switch ($player['post']) {
			case 0:
				$player['post'] = 'goalkeeper';
				break;

			case 1:
				$player['post'] = 'defender';
				break;

			case 2:
				$player['post'] = 'midfielder';
				break;

			case 3:
				$player['post'] = 'forward';
				break;
		}

		return $player;
	}

}
