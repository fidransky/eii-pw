<?php

namespace App\Controllers;

use App\Models\Match\MatchManager;


class HomepageController extends AbstractController {

	private $matchManager;


	public function __construct()
	{
		parent::__construct();

		$this->matchManager = new MatchManager;
	}

	public function getDefault()
	{
		$this->template['pageTitle'] = 'Matches';
		$this->template['ongoingMatches'] = array_map([$this->matchManager, 'process'], $this->matchManager->getOngoing());
		$this->template['finishedMatches'] = array_map([$this->matchManager, 'process'], $this->matchManager->getFinished());
		$this->template['matchStateHandler'] = $this->generatePath('match', 'state');
	}

	public function getMatch()
	{
		$matchId = $_GET['id'];
		$match = $this->matchManager->get($matchId);

		$this->template['title'] = 'Match';
		$this->template['match'] = $this->matchManager->process($match, true);
	}
	
}
