<?php

// constants
define('APP_DIR', '../app/');
define('CONTROLLERS_DIR', 'controllers/');
define('TEMPLATES_DIR', 'templates/');

define('URL', 'http://localhost:8080/eii-pw/');
define('LANG', 'en');
define('TIMEZONE', 'Europe/London');

// PHP settings
session_start();
date_default_timezone_set(TIMEZONE);

// autoloading
spl_autoload_register(function($className) {
	$parts = explode('\\', $className);
	if ($parts[0] !== 'App') return;

	array_shift($parts);
	for ($i = 0; $i < count($parts) - 1; $i++) {
		$parts[$i] = strtolower($parts[$i]);
	}

	$path = APP_DIR . implode('/', $parts) . '.php';
	//var_dump(array($className, $path));

	include_once($path);
});


include(APP_DIR . 'bootstrap.php');
