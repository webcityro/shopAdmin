<?php
namespace Storemaker\App\Models\Users;

use Illuminate\Database\Eloquent\Model;

class Group extends Model {
	protected $table = 'user_groups',
				 $fillable = [
				 	'storeID',
					'name',
					'level',
					'permissions'
				 ];

	public function users()	{
		return $this->hasMany('Storemaker\App\Models\Users\Account', 'groupID', 'id');
	}
}