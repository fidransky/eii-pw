<?php

define('APP_DIR', '../app/');
define('CONTROLLERS_DIR', 'controllers/');
define('TEMPLATES_DIR', 'templates/');


function browse($dir, $interfacesOnly) {
	foreach (glob($dir . '*') as $path) {
		if (is_file($path) && pathinfo($path, PATHINFO_EXTENSION) === 'php') {
			$filename = pathinfo($path, PATHINFO_FILENAME);
			$isInterface = substr($filename, 0, 1) == 'I' && ctype_upper(substr($filename, 0, 2));

			if ($interfacesOnly && !$isInterface) continue;

			include_once($path);
		}

		if (is_dir($path)) {
			browse($path . '/', $interfacesOnly);
		}
	}
}

function autoload() {
	$dirs = [
		APP_DIR . 'models/',
		APP_DIR . 'controllers/',
		APP_DIR . 'utils/',
	];

	$files = [
		APP_DIR . 'Database.php',
		APP_DIR . 'User.php',
		APP_DIR . 'View.php',
	];

	// autoload interfaces
	foreach ($dirs as $dir) {
		browse($dir, true);
	}

	// autoload the rest PHP classes
	foreach ($dirs as $dir) {
		browse($dir, false);
	}

	foreach ($files as $file) {
		include($file);
	}

	include(APP_DIR . 'bootstrap.php');
}


autoload();
