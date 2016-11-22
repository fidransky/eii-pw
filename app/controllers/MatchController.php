<?php

namespace App\Controllers;

use App\Models\AbstractManager;
use App\Models\Match\MatchManager;
use App\Models\Team\TeamManager;
use App\Models\Goal\GoalManager;
use DateTime;


class MatchController extends AbstractSecuredController {

	private $matchManager;
	private $teamManager;
	private $goalManager;


	public function __construct()
	{
		parent::__construct();

		$this->matchManager = new MatchManager;
		$this->teamManager = new TeamManager;
		$this->goalManager = new GoalManager;
	}

	public function getDefault()
	{
		$this->template['title'] = 'Matches';
		$this->template['matches'] = array_map([$this->matchManager, 'process'], $this->matchManager->getAll());
	}

	public function getAdd()
	{
		$this->template['title'] = 'Add a new match';
		$this->template['addHandler'] = $this->generatePath('match', 'add');
		$this->template['teamChangedHandler'] = $this->generatePath('match', 'players');
		$this->template['states'] = $this->getStates();
		$this->template['teams'] = $this->getTeams();
		$this->template['players'] = $this->teamManager->getPlayers($this->template['teams'][0]['id']);
	}

	public function getEdit()
	{
		$id = $_GET['matchId'];
		$match = $this->matchManager->get($id);

		$this->template['title'] = 'Edit match';
		$this->template['editHandler'] = $this->generatePath('match', 'edit') . '?matchId=' . $id;
		$this->template['states'] = $this->getStates();
		$this->template['teams'] = $this->getTeams();

		$this->template['match'] = $this->matchManager->process($match);
		$this->template['match']['homeTeamPlayers'] = $this->matchManager->getPlayers($id, $this->template['match']['home_team_id']);
		$this->template['match']['visitingTeamPlayers'] = $this->matchManager->getPlayers($id, $this->template['match']['visiting_team_id']);
	}

	public function getDelete()
	{
		$id = $_GET['matchId'];

		$result = $this->matchManager->remove($id);

		// redirect
		$this->addFlashMessage('The match was successfully deleted.', 'success');

		$path = $this->generatePath('match');
		$this->redirect($path);
	}

	public function getStart()
	{
		$id = $_GET['matchId'];
		$result = $this->matchManager->setStarted($id);

		// redirect
		$this->addFlashMessage('The match was successfully started.', 'success');
		$this->addFlashMessage('Monitor changes there.', 'info');

		$path = $this->generatePath('match', 'monitor');
		$this->redirect($path . '?matchId=' . $id);
	}

	public function getMonitor()
	{
		$id = $_GET['matchId'];
		$match = $this->matchManager->get($id);

		$this->template['title'] = 'Monitor match';
		$this->template['scoreGoalHandler'] = $this->generatePath('match', 'score');
		$this->template['goalTypes'] = $this->getGoalTypes();

		$this->template['match'] = $this->matchManager->process($match);
		$this->template['match']['homeTeamPlayers'] = $this->matchManager->getPlayers($id, $this->template['match']['home_team_id']);
		$this->template['match']['visitingTeamPlayers'] = $this->matchManager->getPlayers($id, $this->template['match']['visiting_team_id']);
	}

	public function getPause()
	{
		$id = $_GET['matchId'];
		$result = $this->matchManager->setStarted($id, null);

		// redirect
		$this->addFlashMessage('The match was successfully paused.', 'success');

		$path = $this->generatePath('match', 'monitor');
		$this->redirect($path . '?matchId=' . $id);
	}

	public function getResume()
	{
		$id = $_GET['matchId'];
		$result = $this->matchManager->setStarted($id);

		// redirect
		$this->addFlashMessage('The match was successfully resumed.', 'success');

		$path = $this->generatePath('match', 'monitor');
		$this->redirect($path . '?matchId=' . $id);
	}

	public function getEnd()
	{
		$id = $_GET['matchId'];
		$match = $this->matchManager->setEnded($id);

		// redirect
		$this->addFlashMessage('The match was successfully ended.', 'success');

		$path = $this->generatePath('match');
		$this->redirect($path);
	}

	// POST request
	public function postAdd()
	{
		$data = $this->constructMatch();

		$homeTeamPlayers = isset($_POST['homeTeamPlayers']) ? $_POST['homeTeamPlayers'] : [];
		$visitingTeamPlayers = isset($_POST['visitingTeamPlayers']) ? $_POST['visitingTeamPlayers'] : [];
		$players = array_filter(array_unique(array_merge($homeTeamPlayers, $visitingTeamPlayers)));

		try {
			$result = $this->matchManager->saveWithPlayers($data, $players);

			$this->addFlashMessage('The match was successfully created.', 'success');
			$path = $this->generatePath('match');

		} catch (\Exception $e) {
			$this->addFlashMessage('The match was not created.', 'error');
			$path = $this->generatePath('match', 'add');
		}

		// redirect
		$this->redirect($path);
	}

	public function postEdit()
	{
		$id = $_GET['matchId'];
		$data = $this->constructMatch();

		try {
			$result = $this->matchManager->save($data, $id);

			$this->addFlashMessage('The match was successfully saved.', 'success');
			$path = $this->generatePath('match');

		} catch (\Exception $e) {
			$this->addFlashMessage('The match was not saved.', 'error');
			$path = $this->generatePath('match', 'edit') . '?matchId=' . $id;
		}

		// redirect
		$this->redirect($path);
	}

	public function postScore()
	{
		$id = $_POST['matchId'];
		$data = $this->constructGoal();

		try {
			$result = $this->goalManager->save($data);

			$this->addFlashMessage('The goal was successfully saved.', 'success');

		} catch (\Exception $e) {
			$this->addFlashMessage('The goal was not saved.', 'error');
		}

		// redirect
		$path = $this->generatePath('match', 'monitor') . '?matchId=' . $id;
		$this->redirect($path);
	}

	/*
	 * AJAX ENDPOINTS
	 */

	public function getPlayers()
	{
		$id = $_GET['teamId'];
		$players = $this->teamManager->getPlayers($id);

		echo(json_encode($players));
		exit;
	}

	public function getState()
	{
		$id = $_GET['matchId'];
		$match = $this->matchManager->get($id);

		echo(json_encode([
			'started' => $match['started'] ? strtotime($match['started']) : null,
			'part' => $match['part'],
		]));
		exit;
	}

	/*
	 * PRIVATE HELPER METHODS
	 */

	private function getTeams()
	{
		return $this->teamManager->getAll();
	}

	private function getStates()
	{
		return MatchManager::$states;
	}

	private function getGoalTypes()
	{
		return GoalManager::$types;
	}

	private function constructMatch()
	{
		if (empty($_POST['homeTeamId']) || empty($_POST['visitingTeamId'])) {
			$this->addFlashMessage('Please enter all required fields.');

			$path = $this->generatePath('match');
			$this->redirect($path);
		}

		if ($_POST['homeTeamId'] === $_POST['visitingTeamId']) {
			$this->addFlashMessage('Home team and visiting team must differ.');

			$path = $this->generatePath('match');
			$this->redirect($path);
		}

		return [
			'date' => $_POST['date'],
			'home_team_id' => $_POST['homeTeamId'],
			'visiting_team_id' => $_POST['visitingTeamId'],
			'state' => isset($_POST['state']) ? $_POST['state'] : 0,
			'started' => null,
			'home_team_points' => 0,
			'visiting_team_points' => 0,
		];		
	}

	private function constructGoal()
	{
		$id = $_POST['matchId'];
		$teamPlayerId = $_POST['teamPlayerId'];

		$match = $this->matchManager->get($id);

		$now = new DateTime;
		$time = $now->diff(new DateTime($match['started']));

		return [
			'team_player_id' => $teamPlayerId,
			'match_id' => $id,
			'type' => $_POST['type'],
			'part' => $_POST['part'],
			'time' => $time->format('%H:%I:%S'),
		];
	}

}
