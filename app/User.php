<?php

namespace App;


class User {

	/** @var string */
	private $name;
	/** @var boolean */
	private $loggedIn;
	/** @var string */
	private $role;
	

	public function __construct($name, $role)
	{
		$this->setName($name);
		$this->setRole($role);
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

	public function getRole()
	{
		return $this->role;
	}

	public function setRole($role)
	{
		$this->role = $role;
	}

	public function isInRole($role)
	{
		return $this->role === $role;
	}

	public function __get($property)
	{
		return $this->{$property};
	}

}