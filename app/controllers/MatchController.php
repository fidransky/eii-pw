<?php

namespace App\Controllers;

use App\Models\Match\MatchManager;
use App\Models\Team\TeamManager;
use DateTime;


class MatchController extends AbstractSecuredController {

	private $matchManager;
	private $teamManager;


	public function __construct()
	{
		parent::__construct();

		$this->matchManager = new MatchManager;
		$this->teamManager = new TeamManager;
	}

	public function getDefault()
	{
		$states = $this->getStates();

		$this->template['title'] = 'Matches';
		$this->template['matches'] = array_map(function($match) use ($states) {
			$match['homeTeam'] = $this->teamManager->get($match['home_team_id']);
			$match['visitingTeam'] = $this->teamManager->get($match['visiting_team_id']);

			$match['date__raw'] = $match['date'];
			$match['date'] = new DateTime($match['date__raw']);

			$match['state__raw'] = (int) $match['state'];
			$match['state'] = $states[$match['state__raw']];

			return $match;
		}, $this->matchManager->getAll());
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

	public function getPlayers()
	{
		$id = $_GET['teamId'];

		$players = $this->teamManager->getPlayers($id);

		exit(json_encode($players));
	}

	public function getEdit()
	{
		$id = $_GET['matchId'];

		$this->template['title'] = 'Edit match';
		$this->template['editHandler'] = $this->generatePath('match', 'edit') . '?matchId=' . $id;
		$this->template['states'] = $this->getStates();
		$this->template['teams'] = $this->getTeams();

		$this->template['match'] = $this->matchManager->get($id);
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

	private function getTeams()
	{
		return $this->teamManager->getAll();
	}

	private function getStates()
	{
		$states = [];

		$states[] = 'created';
		$states[] = 'ongoing';
		$states[] = 'ended';

		return $states;
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
			'home_team_points' => 0,
			'visiting_team_points' => 0,
		];		
	}

}
