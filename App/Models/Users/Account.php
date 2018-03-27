<?php
namespace Storemaker\App\Models\Users;

use Illuminate\Database\Eloquent\Model;

class Account extends Model {
	protected $table = 'users',
				 $fillable = [
					'fName',
					'lName',
					'userName',
					'password',
					'email',
					'sex',
					'lastLogInDate',
					'lastLogOutDate',
					'groupID',
					'active'
				 ];

	public function group()	{
		return $this->hasOne('Storemaker\App\Models\Users\Group', 'id', 'groupID');
	}
}