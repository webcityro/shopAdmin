<?php
namespace Storemaker\App\Models\Users;
use Storemaker\System\Libraries;

class ForgetPassword extends Model {
	function __construct() {
		parent::__construct();
	}

	public function checkEmail() {
		$email = input::post('email');

		$stx = $this->db->select(userObj::getUsersTable(), 'id', ['email', $email], '', 1);
		return ($stx->getNumRows() > 0) ? true : false;
	}

	public function checkLinkFromEmail($id, $code) {
		$stx = $this->db->select(userObj::getUsersTable(), 'id', ['id', '=', $id, 'AND', 'code', '=', $code], '', 1);
		return ($stx->getNumRows() > 0) ? true : false;
	}

	public function resetPassword()	{
		$userID = input::post('userID');

		try {
			$user = new userObj($userID);
		} catch (Exception $e) {
			die('Tentativa de post ilegal!');
		}

		$salt = hash::salt();
		$hashPassword = hash::make(input::post('newPassword'), $salt);
		$newCode = $user->generateCode();
		return $user->update(['password' => $hashPassword, 'salt' => $salt, 'code' => $newCode]);
	}
}