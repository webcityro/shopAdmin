<?php

class logoutModel extends mainModel {
	function __construct() {
		parent::__construct();
	}

	public function doLogOut() {
		if (cookie::check('hash')) {
			$this->db->delete(self::getUsersSessionTable(), array('hash', cookie::get('hash')), 1);
			cookie::delete('hash');
		}
		$this->db->update(userObj::getUsersTable(), array('lastLogOutDate' => date('Y-m-d H:i:s')), session::get('userID'), 1);
		session::delete('userID');
	}
}

?>