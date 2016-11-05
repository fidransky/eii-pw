<?php

namespace App\Controllers;

use App\Models\League\LeagueManager;


class LeagueController extends AbstractSecuredController {

	private $leagueManager;


	public function __construct()
	{
		parent::__construct();

		$this->leagueManager = new LeagueManager;
	}

	public function getDefault()
	{
		$this->template['title'] = 'Leagues';
		$this->template['leagues'] = $this->leagueManager->getAll();
	}

	public function getAdd()
	{
		$this->template['title'] = 'Add a new league';
		$this->template['addHandler'] = $this->generatePath('league', 'add');
		$this->template['seasons'] = $this->getSeasons();
	}

	public function getEdit()
	{
		$id = $_GET['leagueId'];

		$this->template['title'] = 'Edit league';
		$this->template['editHandler'] = $this->generatePath('league', 'edit') . '?leagueId=' . $id;
		$this->template['seasons'] = $this->getSeasons();

		$this->template['league'] = $this->leagueManager->get($id);
	}

	public function getDelete()
	{
		$id = $_GET['leagueId'];

		$result = $this->leagueManager->remove($id);

		// redirect
		$this->addFlashMessage('The league was successfully deleted.', 'success');

		$path = $this->generatePath('league');
		$this->redirect($path);
	}

	// POST request
	public function postAdd()
	{
		$data = $this->constructLeague();

		$result = $this->leagueManager->save($data);

		// redirect
		$this->addFlashMessage('The league was successfully created.', 'success');

		$path = $this->generatePath('league');
		$this->redirect($path);
	}

	public function postEdit()
	{
		$id = $_GET['leagueId'];
		$data = $this->constructLeague();

		$result = $this->leagueManager->save($data, $id);

		// redirect
		$this->addFlashMessage('The league was successfully saved.', 'success');

		$path = $this->generatePath('league');
		$this->redirect($path);
	}

	private function constructLeague()
	{
		if (empty($_POST['name'])) {
			$this->addFlashMessage('Please enter all required fields.');

			$path = $this->generatePath('league');
			$this->redirect($path);
		}

		return [
			'name' => $_POST['name'],
			'season' => $_POST['season'],
		];		
	}

	private function getSeasons()
	{
		$seasons = [];
		$year = date('Y');

		for ($i = 0; $i < 5; $i++) {
			$seasons[] = $year . ' - ' . ($year + 1);
			$year++;
		}

		return $seasons;
	}

}
