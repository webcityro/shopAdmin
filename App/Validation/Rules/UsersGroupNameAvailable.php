<?php

namespace Storemaker\App\Validation\Rules;

use Respect\Validation\Rules\AbstractRule;
use Storemaker\App\Models\Users\Group;

class UsersGroupNameAvailable extends AbstractRule {
	private $groupID;

	function __construct($groupID = false) {
		$this->groupID = $groupID;
	}

	public function validate($input)	{
		$group = Group::where('name', $input);

		if ($this->groupID) {
			$group = $group->where('id', '!=', $this->groupID);
		}

		return $group->count() === 0;
	}
}