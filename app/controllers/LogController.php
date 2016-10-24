<?php

namespace App\Controllers;


class LogController extends AbstractController {

	// GET request
	public function getIn()
	{
		$this->template['title'] = 'Log in';
		$this->template['logInHandler'] = $this->generatePath('log', 'in');
	}

	public function getOut()
	{
		$_SESSION['loggedIn'] = false;

		$path = $this->generatePath('homepage');
		$this->redirect($path);
	}

	// POST request
	public function postIn()
	{
		if (empty($_POST['username']) || empty($_POST['password'])) {
			$this->addFlashMessage('Please enter credentials.');

			$path = $this->generatePath('log', 'in');
			$this->redirect($path);			
		}

		$_SESSION['loggedIn'] = true;
		$_SESSION['id'] = 1;

		$path = $this->generatePath('backend');
		$this->redirect($path);
	}

}
