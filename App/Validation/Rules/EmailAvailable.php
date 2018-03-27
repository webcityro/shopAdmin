<?php

namespace Storemaker\App\Validation\Rules;

use Respect\Validation\Rules\AbstractRule;
use Storemaker\App\Models\Users\Account;

class EmailAvailable extends AbstractRule {
	private $userID;

	function __construct($userID = false) {
		$this->userID = $userID;
	}

	public function validate($input)	{
		$user = Account::where('email', $input);

		if ($this->userID) {
			$user = $user->where('id', '!=', $this->userID);
		}

		return $user->count() === 0;
	}
}