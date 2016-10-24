<?php

namespace App;


class View {

	private $basePath = __DIR__ . '/../www/';

	private $templatesPath = __DIR__ . '/' . TEMPLATES_DIR;

	private $template;

	private $user;

	private $flashMessages;

	private $variables;


	public function setTemplate($template)
	{
		$this->template = $template;
	}

	public function setUser($user)
	{
		$this->user = $user;
	}

	public function setFlashMessages($flashMessages)
	{
		$this->flashMessages = $flashMessages;
	}

	public function setVariables($variables)
	{
		$this->variables = $variables;
	}

	public function render()
	{
		if (!file_exists($this->templatesPath . $this->template)) {
			exit('Failed: template file does not exist');
		}

		$basePath = URL;
		$templatesPath = $this->templatesPath;
		$template = $this->template;

		foreach ($this->variables as $key => $value) {
			${$key} = $value;
		}

		$user = $this->user;
		$flashMessages = $this->flashMessages;

		//var_dump(get_defined_vars());
		
		include($templatesPath . 'layout.php');		
	}

}
