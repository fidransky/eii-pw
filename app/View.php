<?php

namespace App;


/**
 * @author Pavel Fidransky [jsem@pavelfidransky.cz]
 */
class View {

	/** @var string */
	private $basePath = __DIR__ . '/../www/';
	/** @var string */
	private $templatesPath = __DIR__ . '/' . TEMPLATES_DIR;
	/** @var string */
	private $template;
	/** @var User */
	private $user;
	/** @var array */
	private $flashMessages;
	/** @var array */
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
		$appName = 'App';

		unset($key, $value);

		define('DATETIME_FORMAT', 'F d, H:i');
		define('HTML5_DATE_FORMAT', 'Y-m-d');
		define('HTML5_TIME_FORMAT', 'H:i:s');
		define('HTML5_DATETIME_FORMAT', 'Y-m-d\TH:i:s');

		//var_dump(get_defined_vars());
		
		include($templatesPath . 'layout.php');		
	}

}
