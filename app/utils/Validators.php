<?php

namespace App\Utils;


class Validators {
	
	public static function validateEmail($mail)
	{
		return filter_var($mail, FILTER_VALIDATE_EMAIL);
	}

}
