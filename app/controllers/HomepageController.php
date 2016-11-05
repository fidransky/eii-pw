<?php

namespace App\Controllers;


class HomepageController extends AbstractController {

	public function getDefault()
	{
		$this->template['title'] = 'Homepage';
	}
	
}
