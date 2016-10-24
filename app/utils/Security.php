<?php

namespace App\Utils;


class Security {

	public static function encrypt($password)
	{
		return bcrypt($password);
	}
	
}
