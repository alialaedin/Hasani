<?php

namespace App\Classes;

use Illuminate\Validation\ValidationException;

class Helpers {
    public static function makeWebValidationException($message, $key = 'unknown', $errorBag = 'default'): ValidationException
	{
		return ValidationException::withMessages([
			$key => [$message]
		])
			->errorBag($errorBag);
	}
}