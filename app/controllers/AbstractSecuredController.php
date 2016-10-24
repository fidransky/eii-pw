<?php

namespace App\Controllers;


abstract class AbstractSecuredController extends AbstractController {
	
	public function startup()
	{
		parent::startup();

		if (!$this->user->isLoggedIn()) {
			$this->addFlashMessage('You are not allowed to enter.', 'error');

			$path = $this->generatePath('log', 'in');
			$this->redirect($path);
		}
	}

}
