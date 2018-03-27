<?php

namespace Storemaker\App\Validation\Rules;

use Respect\Validation\Rules\AbstractRule;
use Storemaker\App\Models\Users\Account;

class CheckCurrentPassword extends AbstractRule {
	private $user;

	function __construct($user) {
		$this->user = $user;
	}

	public function validate($input)	{
		return password_verify($input, $this->user->password);
	}
}