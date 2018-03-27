<?php

namespace Storemaker\App\Validation\Exceptions;

use Respect\Validation\Exceptions\ValidationException;

class UserNameAvailableException extends ValidationException {
	public static $defaultTemplates = [
		self::MODE_DEFAULT => [
			self::STANDARD => ['customMsg' => 'validateUserNameAvailable']
		]
	];
}