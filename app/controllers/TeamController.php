<?php

namespace App\Controllers;

use App\Models\Team\TeamManager;
use App\Models\League\LeagueManager;


class TeamController extends AbstractSecuredController {

	private $teamManager;
	private $leagueManager;


	public function __construct()
	{
		parent::__construct();

		$this->teamManager = new TeamManager;
		$this->leagueManager = new LeagueManager;
	}

	public function getDefault()
	{
		$this->template['title'] = 'Teams';
		$this->template['teams'] = $this->teamManager->getAll();
	}

	public function getAdd()
	{
		$this->template['title'] = 'Add a new team';
		$this->template['addHandler'] = $this->generatePath('team', 'add');
		$this->template['leagues'] = $this->getLeagues();
	}

	public function getEdit()
	{
		$id = $_GET['teamId'];

		$this->template['title'] = 'Edit team';
		$this->template['editHandler'] = $this->generatePath('team', 'edit') . '?teamId=' . $id;
		$this->template['leagues'] = $this->getLeagues();

		$this->template['team'] = $this->teamManager->get($id);
		$this->template['team']['league'] = $this->teamManager->getLeague($id);
	}

	public function getDelete()
	{
		$id = $_GET['teamId'];

		$result = $this->teamManager->remove($id);

		// redirect
		$this->addFlashMessage('The team was successfully deleted.', 'success');

		$path = $this->generatePath('team');
		$this->redirect($path);
	}

	// POST request
	public function postAdd()
	{
		$data = $this->constructTeam();

		try {
			$result = $this->teamManager->save($data);

			$this->addFlashMessage('The team was successfully created.', 'success');

			$path = $this->generatePath('team');

		} catch (\Exception $e) {
			$this->addFlashMessage('The team was not created.', 'error');

			$path = $this->generatePath('team', 'add');
		}

		// redirect
		$this->redirect($path);
	}

	public function postEdit()
	{
		$id = $_GET['teamId'];
		$data = $this->constructTeam();

		$result = $this->teamManager->save($data, $id);

		// redirect
		$this->addFlashMessage('The team was successfully saved.', 'success');

		$path = $this->generatePath('team');
		$this->redirect($path);
	}

	private function getLeagues()
	{
		return $this->leagueManager->getAll();
	}

	private function constructTeam()
	{
		if (empty($_POST['name'])) {
			$this->addFlashMessage('Please enter all required fields.');

			$path = $this->generatePath('team');
			$this->redirect($path);
		}

		return [
			'name' => $_POST['name'],
			'stadium' => $_POST['stadium'],
			'leagueId' => $_POST['league'],
		];		
	}

}
