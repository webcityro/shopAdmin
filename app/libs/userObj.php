<?php

class userObj {
	private static $instance,
				   $db,
				   $usersTable,
				   $usersSessionTable;
	private $data,
			$loggedIn,
			$isOwner;

	function __construct($user = null) {
		if (empty($user)) {
			if (!session::check('userID') && cookie::check('hash')) {
				$hash = cookie::get('hash');
				$hashRow = self::$db->select(self::$usersSessionTable, 'userID', array('hash', $hash), '', 1);

				if ($hashRow->getNumRows() == 1) {
					$this->loggedIn = true;
					$hashRow = $hashRow->results();
					session::set('userID', $hashRow->userID);
				}
			} else if (session::check('userID')) {
				$this->loggedIn = true;
			}
			$this->find(session::get('userID'));
		} else {
			$this->find($user);
			if (!$this->exists()) {
				throw new Exception(language::translate('userNotFond'));
			}
		}

		if ($this->exists()) {
			$this->isOwner = ($this->getData()->id == config::get('ownerID')) ? true : false;
		}
	}

	public static function getUsersTable()	{
		return self::$usersTable;
	}

	public static function getUsersSessionTable()	{
		return self::$usersSessionTable;
	}

	public static function init() {
		if (empty(self::$instance)) {
			self::$usersTable = config::get('database/prefix').'users';
			self::$usersSessionTable = config::get('database/prefix').'users_sessions';
			self::$db = database::init();
			self::$instance = new userObj();
		}
		return self::$instance;
	}

	public function isLogIn() {
		return $this->loggedIn;
	}

	public function isOwner() {
		return $this->isOwner;
	}

	public function find($user, $fields = '*') {
		if (ctype_digit($user)) {
			$field = 'id';
		} else {
			$field = (filter_var($user, FILTER_VALIDATE_EMAIL)) ? 'email' : 'userName';
		}

		$userQuery = self::$db->select(self::$usersTable, $fields, array($field, $user), '', 1);

		if ($userQuery->getNumRows() == 1) {
			$this->data = $userQuery->results()[0];
			return true;
		}

		return false;
	}

	public function getData() {
		return $this->data;
	}

	public function exists() {
		return (!empty($this->data)) ? true : false;
	}

	public function update($data, $uID = false) {
		$id = (!$uID) ? $this->getData()->id : $uID;
		return self::$db->update(self::$usersTable, $data, $id, 1);
	}

	public function generateCode() {
		$charset = 'abcdefghijklmnopqrstuvwyxzABCDEFGHIJKLMNOPQRSTUVWYXZ0123456789`-=\\[];\',./~!@#$%&*()_+|{}:"<>?';
		$code = md5(str_shuffle($charset).rand(0, 999999999999999999));

		if ($this->checkCode($code)) {
			$code = $this->generateCode();
		}

		return $code;
	}

	public function checkCode($code) {
		$stx = self::$db->select(self::$usersTable, 'id', ['code', $code], '', 1);
		return ($stx->getNumRows() == 1) ? true : false;
	}

	public function delete() {
		if (config::get('ownerID') == $this->data->id) {
			die('Nu se poate sterge proprietarul!');
		}
		self::$db->delete(self::$usersTable, $this->data->id);
		$dir = config::get('path/users').$this->data->id;
		if (dir::exists($dir)) {
			return dir::remove($dir);
		}
	}
}