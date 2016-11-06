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

	public function save($data, $id = null)
	{
		$teamId = $data['teamId'];
		unset($data['teamId']);

		$result = parent::save($data, $id);

		if ($result) {
			if ($id) {
				return true;

			} else {
				$query = 'INSERT INTO team_player(team_id, player_id) VALUES (:teamId, :playerId)';
				$args = [
					':teamId' => $teamId,
					':playerId' => $result,
				];

				$this->database->query($query, $args);

				return $result;
			}
		}

		return false;
	}

	public function getTeam($playerId)
	{
		$query = 'SELECT team.* FROM team_player LEFT JOIN team ON team.id = team_player.team_id WHERE team_player.player_id = :playerId LIMIT 1';
		$args = [
			':playerId' => $playerId,
		];

		return $this->database
			->query($query, $args)
			->fetch(PDO::FETCH_ASSOC);		
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
