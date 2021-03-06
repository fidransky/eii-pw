<?php

namespace App\Controllers;

use App;
use App\User;
use App\View;
use App\Models\User\UserManager;


abstract class AbstractController {

	const FLASH_MESSAGES_NAME = 'flashMessages';
	const FLASH_MESSAGES_TIMEOUT = 0.5;

	protected $user;

	protected $flashes = [];
	protected $template = [];

	protected $userManager;


	public function __construct()
	{
		$this->userManager = new UserManager;
	}

	public function init($action)
	{
		$this->startup();
		$this->run($action);
		$this->shutdown();
	}

	protected function startup()
	{
		// user
		if (isset($_SESSION['id'])) {
			$user = $this->userManager->get($_SESSION['id']);

			$this->user = new User($user['name'], $user['role']);
			$this->user->setLoggedIn($_SESSION['loggedIn']);

		} else {
			$this->user = new User(null, null);
		}

		// flashes
		if (isset($_SESSION[self::FLASH_MESSAGES_NAME])) {
			$time = microtime(true);
			foreach ($_SESSION[self::FLASH_MESSAGES_NAME] as $key => $flashMessage) {
				$flashMessage = json_decode($flashMessage, true);

				if (($time - $flashMessage['created']) > self::FLASH_MESSAGES_TIMEOUT) {
					unset($_SESSION[self::FLASH_MESSAGES_NAME][$key]);
				} else {
					$this->flashes[] = $flashMessage;
				}
			}
		}
	}

	protected function run($action)
	{
		// set up template file name
		$controller = $this->getControllerName();
		$template = str_replace('Controller', '', $controller) . '/' . $action . '.php';

		// set up view
		$view = new View;
		$view->setTemplate($template);

		// check if action method exists
		$method = $this->getMethodName($action, $_SERVER['REQUEST_METHOD']);
		if (method_exists($this, $method) === false) {
			App\fail('Failed: action method does not exist', 500);
			exit;
		}

		// run the action method
		$this->{$method}();

		// set template variables
		$view->setUser($this->user);
		$view->setFlashMessages($this->flashes);
		$view->setVariables($this->template);

		// render the template
		$view->render();
	}

	protected function shutdown()
	{
		exit;
	}

	protected function addFlashMessage($message, $type = 'info')
	{
		$_SESSION[self::FLASH_MESSAGES_NAME][] = json_encode([
			'text' => $message,
			'type' => $type,
			'created' => microtime(true),
		]);
	}

	protected function generatePath($controller, $action = 'default', $nice = true)
	{
		$setAction = $action && $action != 'default';

		if ($nice) {
			return URL . $controller . ($setAction ? '/' . $action : '');
		} else {
			return URL . 'index.php?controller=' . $controller . ($setAction ? '&action=' . $action : '');
		}
	}

	protected function redirect($path, $statusCode = 303)
	{
		if (headers_sent()) {
			exit('Failed: headers are already sent, cannot redirect');
		}

		header('Location: ' . $path, true, $statusCode);
		exit;
	}

	private function getClassName()
	{
		return get_class($this);
	}

	private function getSimpleClassName()
	{
		return str_replace(__NAMESPACE__ . '\\', '', $this->getClassName());
	}

	private function getControllerName()
	{
		return str_replace('Controller', '', $this->getSimpleClassName());
	}

	private function getMethodName($action, $method = 'GET')
	{
		switch ($method) {
			case 'GET':
				return 'get' . ucfirst($action);

			case 'POST':
				return 'post' . ucfirst($action);

			case 'PUT':
				return 'put' . ucfirst($action);

			case 'DELETE':
				return 'delete' . ucfirst($action);
		}		
	}

}
