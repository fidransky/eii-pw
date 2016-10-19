<?php

namespace App;


// routes
$default = [
	'controller' => 'Homepage',
	'action' => 'default',
];

if (isset($_GET['page'])) {
	$page = ucfirst($_GET['page']);

	switch ($page) {
		case 'Homepage':
			$controller = 'Homepage';
			break;

		default:
			$controller = $default['controller'];
	}

	if (isset($_GET['action'])) {
		$action = $_GET['action'];		

		switch ($action) {
			case 'other':
				$action = 'other';
				break;

			default:
				$action = $default['action'];
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
	exit('Failed: controller class does not exist');
}

$self = new $controller;
$self->init($action);