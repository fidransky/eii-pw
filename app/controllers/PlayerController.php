<?php

namespace App\Controllers;

use App\Models\Player\PlayerManager;


class PlayerController extends AbstractSecuredController {

	private $playerManager;


	public function __construct()
	{
		parent::__construct();

		$this->playerManager = new PlayerManager;
	}

	public function getDefault()
	{
		$this->template['title'] = 'Players';
		$this->template['players'] = $this->playerManager->getAll();
	}

}
