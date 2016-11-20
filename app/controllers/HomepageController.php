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
		$this->template['title'] = 'Homepage';
		$this->template['matches'] = $this->matchManager->getOngoing();
	}
	
}
