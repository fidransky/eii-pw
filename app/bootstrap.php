<?php

namespace App;

use SplFileInfo;


// debug information
function fail($cause, $statusCode) {
	echo($cause);

	if (DEBUG) {
		$stacktrace = debug_backtrace();
		foreach ($stacktrace as $index => $trace) {
			$file = new SplFileInfo($trace['file']);
			echo('<p>' . ($index + 1) . '. <code>' . $file->getPath() . DIRECTORY_SEPARATOR . '<strong>' . $file->getFilename() . ':' . $trace['line'] . '</strong></code></p>');
		}
	}

	http_response_code($statusCode);
}


// routes
$default = [
	'controller' => 'Homepage',
	'action' => 'default',
];


if (isset($_GET['page'])) {
	$page = ucfirst($_GET['page']);

	switch ($page) {
		default:
			if (empty($page)) {
				$controller = $default['controller'];
			} else {
				$controller = $page;
			}
	}

	if (isset($_GET['action'])) {
		$action = $_GET['action'];		

		switch ($action) {
			default:
				if (empty($action)) {
					$action = $default['action'];
				}
		}
	} else {
		$action = $default['action'];
	}

} else {
	$controller = $default['controller'];
	$action = $default['action'];
}


// set controller class name
$controller = __NAMESPACE__ . '\\Controllers\\' . $controller . 'Controller';

// check if controller class exists
if (class_exists($controller) === false) {
	fail('Failed: controller class does not exist', 500);
	exit;
}

$self = new $controller;
$self->init($action);
