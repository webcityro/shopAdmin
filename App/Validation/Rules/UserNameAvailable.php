<?php

namespace Storemaker\App\Validation\Rules;

use Respect\Validation\Rules\AbstractRule;
use Storemaker\App\Models\Users\Account;

class UserNameAvailable extends AbstractRule {
	public function validate($input)	{
		return Account::where('userName', $input)->count() === 0;
	}
}