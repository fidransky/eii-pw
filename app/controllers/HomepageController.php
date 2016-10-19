<?php

namespace App\Controllers;


class HomepageController extends AbstractController {

	private $db;


	protected function startup()
	{
		parent::startup();

		//$this->db = new \Database('localhost', 'admin', 'admin', 'thunder');
	}

	protected function shutdown()
	{
		parent::shutdown();

		//$this->db->__destruct();
	}

	public function renderDefault()
	{
		$this->scope['title'] = 'Homepage';
		$this->scope['foo'] = 'bar';
	}

	public function renderOther()
	{
		$this->scope['title'] = 'Other';
		$this->scope['lorem'] = 'ipsum';
	}
	
}
