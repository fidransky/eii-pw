<?php

namespace App\Controllers;

use App\Utils\Security;
use App\Utils\Validators;


class LogController extends AbstractController {

	// GET requests
	public function getIn()
	{
		$this->template['title'] = 'Log in';
		$this->template['logInHandler'] = $this->generatePath('log', 'in');
	}

	public function getOut()
	{
		unset($_SESSION['id']);
		$_SESSION['loggedIn'] = false;

		$path = $this->generatePath('homepage');
		$this->redirect($path);
	}

	// POST request
	public function postIn()
	{
		if (empty($_POST['mail']) || empty($_POST['password'])) {
			$this->addFlashMessage('Please enter credentials.');

			$path = $this->generatePath('log', 'in');
			$this->redirect($path);			
		}

		if (Validators::validateEmail($_POST['mail']) === false) {
			$this->addFlashMessage('E-mail you entered is not valid.');

			$path = $this->generatePath('sign', 'in');
			$this->redirect($path);
		}

		// find the user
		$user = $this->userManager->getByMail($_POST['mail']);

		// log in
		$_SESSION['loggedIn'] = Security::match($_POST['password'], $user['password']);
		$_SESSION['id'] = $user['id'];

		// redirect
		$path = $this->generatePath('user');
		$this->redirect($path);
	}

}
