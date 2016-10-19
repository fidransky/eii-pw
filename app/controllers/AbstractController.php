<?php

namespace App\Controllers;

use App\View;


abstract class AbstractController {

	protected $scope = [];



	public function init($action)
	{
		$this->startup();
		$this->run($action);
		$this->shutdown();
	}

	protected function startup()
	{
	}

	protected function run($action)
	{
		// set up template file name
		$controller = $this->getControllerName();
		$template = str_replace('Controller', '', $controller) . '/' . $action . '.php';

		// set up view
		$view = new View;
		$view->setTemplate($template);

		// check if render method exists
		$method = $this->getRenderMethodName($action);
		if (method_exists($this, $method) === false) {
			exit('Failed: action method does not exist');
		}

		// run render method
		$this->{$method}();

		// set template variables and render view
		$view->setScope($this->scope);
		$view->render();
	}

	protected function shutdown()
	{
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

	private function getRenderMethodName($action)
	{
		return 'render' . ucfirst($action);
	}

}
