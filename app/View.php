<?php

namespace App;


class View {

	private $basePath = __DIR__ . '/../www/';

	private $templatesPath = __DIR__ . '/' . TEMPLATES_DIR;

	private $template;

	private $scope;


	public function setTemplate($template)
	{
		if (!file_exists($this->templatesPath . $template)) {
			exit('Failed: template file does not exist');
		}

		$this->template = $template;
	}

	public function setScope($scope)
	{
		$this->scope = $scope;
	}

	public function render()
	{
		$basePath = URL;
		$templatesPath = $this->templatesPath;
		$template = $this->template;

		foreach ($this->scope as $key => $value) {
			${$key} = $value;
		}

		include($templatesPath . 'layout.php');		
	}

}
