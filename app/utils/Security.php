<?php

namespace App\Utils;


class Security {

	public static function encrypt($password)
	{
		return password_hash($password, PASSWORD_BCRYPT);
	}

	public static function match($password, $hash)
	{
		return password_verify($password, $hash);
	}
	
}
