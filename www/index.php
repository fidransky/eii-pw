<?php
define('URL', 'http://localhost:8080/eii-pw/');
define('LANG', 'en');
define('TIMEZONE', 'Europe/London');

define('APP_DIR', '../app/');
define('CONTROLLERS_DIR', 'controllers/');
define('TEMPLATES_DIR', 'templates/');

session_start();
date_default_timezone_set(TIMEZONE);

autoload();


function autoload() {
	$dirs = [
		APP_DIR . 'controllers/',
		APP_DIR . 'libs/',
	];

	foreach ($dirs as $dir) {
		browse($dir);
	}

	include(APP_DIR . 'View.php');

	include(APP_DIR . 'bootstrap.php');
}

function browse($dir) {
	foreach (glob($dir . '*') as $path) {
		if (is_file($path) && pathinfo($path, PATHINFO_EXTENSION) === 'php') {
			include($path);
		}

		if (is_dir($path)) {
			browse($path . '/');
		}
	}
}