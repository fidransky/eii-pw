<?php

define('APP_DIR', '../app/');
define('CONTROLLERS_DIR', 'controllers/');
define('TEMPLATES_DIR', 'templates/');


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

function autoload() {
	$dirs = [
		APP_DIR . 'controllers/',
		APP_DIR . 'libs/',
		APP_DIR . 'utils/',
	];

	foreach ($dirs as $dir) {
		browse($dir);
	}

	include(APP_DIR . 'User.php');
	include(APP_DIR . 'View.php');

	include(APP_DIR . 'bootstrap.php');
}


autoload();
