<?php
namespace Storemaker\App\Models\Users;
use Storemaker\System\Libraries;

class Profile extends Model {
	function __construct() {
		parent::__construct();
	}

	public function checkEmailExists($id, $email) {
		$stx = $this->db->select(userObj::getUsersTable(), '*', ['id', '!=', $id, 'AND', 'email', '=', $email], '', 1);
		return ($stx->getNumRows() > 0) ? true : false;
	}
}