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

	public function getDefault()
	{
		$this->template['title'] = 'Homepage';
		$this->template['foo'] = 'bar';
	}

	public function getOther()
	{
		$this->template['title'] = 'Other';
		$this->template['lorem'] = 'ipsum';
	}
	
}
