<?php

namespace App\Controllers;

use App\Models\Player\PlayerManager;
use App\Models\Team\TeamManager;


class PlayerController extends AbstractSecuredController {

	private $playerManager;
	private $teamManager;


	public function __construct()
	{
		parent::__construct();

		$this->playerManager = new PlayerManager;
		$this->teamManager = new TeamManager;
	}

	public function getDefault()
	{
		$this->template['title'] = 'Players';
		$this->template['players'] = $this->playerManager->getAll();
	}

	public function getAdd()
	{
		$this->template['title'] = 'Add a new player';
		$this->template['addHandler'] = $this->generatePath('player', 'add');
		$this->template['posts'] = $this->getPosts();
		$this->template['teams'] = $this->getTeams();
	}

	public function getEdit()
	{
		$id = $_GET['playerId'];

		$this->template['title'] = 'Edit player';
		$this->template['editHandler'] = $this->generatePath('player', 'edit') . '?teamId=' . $id;
		$this->template['posts'] = $this->getPosts();
		$this->template['teams'] = $this->getTeams();

		$this->template['player'] = $this->playerManager->get($id);
		$this->template['player']['team'] = $this->playerManager->getTeam($id);
	}

	public function getDelete()
	{
		$id = $_GET['playerId'];

		$result = $this->playerManager->remove($id);

		// redirect
		$this->addFlashMessage('The player was successfully deleted.', 'success');

		$path = $this->generatePath('player');
		$this->redirect($path);
	}

	// POST request
	public function postAdd()
	{
		$data = $this->constructPlayer();

		try {
			$result = $this->playerManager->save($data);

			$this->addFlashMessage('The player was successfully created.', 'success');

			$path = $this->generatePath('player');

		} catch (\Exception $e) {
			$this->addFlashMessage('The player was not created.', 'error');

			$path = $this->generatePath('player', 'add');
		}

		// redirect
		$this->redirect($path);
	}

	public function postEdit()
	{
		$id = $_GET['playerId'];
		$data = $this->constructPlayer();

		$result = $this->playerManager->save($data, $id);

		// redirect
		$this->addFlashMessage('The player was successfully saved.', 'success');

		$path = $this->generatePath('player');
		$this->redirect($path);
	}

	private function getTeams()
	{
		return $this->teamManager->getAll();
	}

	private function getPosts()
	{
		$posts = [];

		$posts[] = 'goalkeeper';
		$posts[] = 'defender';
		$posts[] = 'midfielder';
		$posts[] = 'forward';

		return $posts;
	}

	private function constructPlayer()
	{
		if (empty($_POST['name'])) {
			$this->addFlashMessage('Please enter all required fields.');

			$path = $this->generatePath('player');
			$this->redirect($path);
		}

		return [
			'name' => $_POST['name'],
			'number' => $_POST['number'],
			'post' => $_POST['post'],
			'teamId' => $_POST['team'],
		];		
	}

}
