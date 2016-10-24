<?php

namespace App;


class User {
	/** @var string */
	private $name;
	/** @var boolean */
	private $loggedIn;
	

	public function __construct($name)
	{
		$this->setName($name);
		$this->setLoggedIn(false);
	}
	
	public function getName()
	{
		return $this->name;
	}

	public function setName($name)
	{
		$this->name = $name;
	}

	public function isLoggedIn()
	{
		return $this->loggedIn;
	}

	public function setLoggedIn($loggedIn)
	{
		$this->loggedIn = $loggedIn;
	}

	public function __get($property)
	{
		return $this->{$property};
	}

}