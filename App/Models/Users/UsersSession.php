<?php
namespace Storemaker\App\Models\Users;

use Illuminate\Database\Eloquent\Model;

class UsersSession extends Model {

	protected $table = 'users_sessions',
				 $fillable = ['userID', 'hash'];
}