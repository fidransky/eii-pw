<?php

namespace App\Controllers;

use App\Models\Match\MatchManager;
use App\Models\Team\TeamManager;


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
		$this->template['title'] = 'Matches';
		$this->template['matches'] = $this->matchManager->getAll();
	}

}
