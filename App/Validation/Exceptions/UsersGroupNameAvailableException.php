<?php

namespace Storemaker\App\Validation\Exceptions;

use Respect\Validation\Exceptions\ValidationException;

class UsersGroupNameAvailableException extends ValidationException {
	public static $defaultTemplates = [
		self::MODE_DEFAULT => [
			self::STANDARD => ['customMsg' => 'validateGroupNameAvailable']
		]
	];
}