<?php
namespace Storemaker\App\Models\Users;

use Illuminate\Database\Eloquent\Model;

class ForgetPassword extends Model {
	protected $table = 'users_password_reset',
				 $fillable = ['userID', 'token'];

	public function user() {
		return $this->belongsTo('Storemaker\App\Models\Users\Account', 'userID', 'id');
	}
}