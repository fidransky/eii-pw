<?php

namespace App\Controllers;

use App\Utils\Security;
use App\Utils\Validators;


class SignController extends AbstractController {

	// GET request
	public function getIn()
	{
		$this->template['title'] = 'Sign in';
		$this->template['signInHandler'] = $this->generatePath('sign', 'in');
	}

	// POST request
	public function postIn()
	{
		if (empty($_POST['name']) || empty($_POST['mail']) || empty($_POST['password'])) {
			$this->addFlashMessage('Please enter all required fields.');

			$path = $this->generatePath('sign', 'in');
			$this->redirect($path);
		}

		if (Validators::validateEmail($_POST['mail']) === false) {
			$this->addFlashMessage('E-mail you entered is not valid.');

			$path = $this->generatePath('sign', 'in');
			$this->redirect($path);
		}

		if ($_POST['password'] !== $_POST['password_check']) {
			$this->addFlashMessage('Please retype your password and password check.');

			$path = $this->generatePath('sign', 'in');
			$this->redirect($path);
		}

		// save a new user
		$data = [
			'name' => $_POST['name'],
			'mail' => $_POST['mail'],
			'password' => Security::encrypt($_POST['password']),
			'role' => 'regular',
		];

		$result = $this->userManager->save($data);

		// log in
		$_SESSION['loggedIn'] = true;
		$_SESSION['id'] = $result;

		// redirect
		$path = $this->generatePath('user');
		$this->redirect($path);
	}

}
