<?php

class singupModel extends mainModel {
	private $userCode,
			$userID,
			$userGroup = 1;

	function __construct() {
		parent::__construct();
	}

	public function checkExists($user) {
		if (ctype_digit($user)) {
			$field = 'id';
		} else {
			$field = (filter_var($user, FILTER_VALIDATE_EMAIL)) ? 'email' : 'userName';
		}

		$userQuery = $this->db->select(userObj::getUsersTable(), 'id', array($field, $user), '', 1);
		return ($userQuery->getNumRows() == 1) ? true : false;
	}

	public function insertUser() {
		do {
			$this->userCode = $this->generateUserCode();

			$query = $this->db->select(userObj::getUsersTable(), 'id', array('code', $this->userCode), '', 1);
			$codeCount = $query->getNumRows();
		} while ($codeCount == 1);

		$salt = hash::salt();

		$insertData = array('fName' => input::escape(input::post('fName')),
					   'lName' => input::escape(input::post('lName')),
					   'userName' => input::escape(input::post('userName')),
				       'password' => hash::make(input::post('password'), $salt),
				       'salt' => $salt,
				       'email' => input::escape(input::post('email')),
				       'sex' => input::escape(input::post('sex')),
				       'code' => $this->userCode,
				       'active' => '0',
				       'inActiveDate' => date('Y-m-d'));

		$insertUser = $this->db->insert(userObj::getUsersTable(), $insertData);

		if (!$insertUser->error()) {
			$id = $this->db->getLastInsertID();
			return $id;
		} else {
			// die(print_r($this->db->getError()));
			return false;
		}
	}

	public function createUserDir($userID) {
		return mkdir(config::get('path/users').$userID, 0777);
	}

	public function isJquery() {
		return (input::post('jQuery') == 'true') ? true : false;
	}

	private function generateUserCode() {
		$charset = 'abcdefghijklmnopqrstuvwyxzABCDEFGHIJKLMNOPQRSTUVWYXZ0123456789`-=\\[];\',./~!@#$%&*()_+|{}:"<>?';
		return md5(str_shuffle($charset).rand(0, 999999999999999999));
	}

	public function emailUser($userId) {
		$lName = input::post('fName');
		$userName = input::post('userName');
		$password = input::post('password');
		$email = input::post('email');
		$newUserId = $userId;
		$userCode = $this->userCode;
		$style = config::get('site/domain').'app/public/styles/'.config::get('site/style').'/';

		$body = '<p>'.language::translate('hi').' '.$userName.'!</p>
		<p>'.language::translate('emailPharagraf1').'</p>
		<p>'.language::translate('emailPharagraf2').'</p>

		<ul>
			<li>'.language::translate('formLabelUserName').' <strong>'.$userName.'</strong></li>
			<li>'.language::translate('formLabelPassword').' <strong>'.$password.'</strong></li>
			<li>'.language::translate('formLabelEmail').'Adresa de email: <strong>'.$email.'</strong></li>
			<li>'.language::translate('yourProfile').': <strong><a href="'.config::get('site/domain').'user/'.$userName.'/">'.config::get('site/domain').'user/'.$userName.'/</a></strong></li>
		</ul>

		<p>'.language::translate('emailPharagraf3').' <a href="'.config::get('site/domain').'singup/activate/'.$newUserId.'/'.$userCode.'">'.language::translate('clickHire').'</a>!</p>
		<p>'.language::translate('tnx').'<br />'.language::translate('ourTeam').'</p>';

		$mail = new  email();

		$mail->setTo($email);
		$mail->setFrom(config::get('site/name').' <no-reply@'.str_replace(array('/', 'http:'), '', config::get('site/domain')).'>');
		$mail->setSubject(language::translate('emailSubject'));
		$mail->setBody(language::translate('emailSubject'), $body);

		return $mail->send();
	}

	public function showEmailUser() {
		$title = 'email title';
		$value = 'hybngnbfv ftbv  fgybv fgvb fgvb ytghnm tgbv ygjffgf';
		include_once config::get('path/appViews').'tamplate/styles/default/mail.php';
		die($body);
	}

	public function getUserEmail() {
		return input::post('email');
	}

	public function deleteprofil($userID) {
		$this->db->delete('profiles', 'userID='.$userID);
	}

	public function activateUser($userID, $code) {
		$userRow = $this->db->select(userObj::getUsersTable(), 'id, code, active', array('id', '=', $userID, 'AND', 'code', '=', $code), '', 1)->results();
		$userCount = $this->db->getNumRows();

		if ($userCount == 1) {
			if ($userRow->active == 0) {
				$update = $this->db->update(userObj::getUsersTable(), array('active' => '1'), array('id', '=', $userID, 'AND', 'code', '=', $code), 1);

				if ($update) {
					return ['type' => 'success', 'msg' => language::translate('emailActivatedSuccess')];
				}
			} else {
				return ['type' => 'error', 'msg' => language::translate('emailActivatedBefoe')];
			}
		}
		return ['type' => 'error', 'msg' => language::translate('emailActivatedFail')];
	}

	public function ajax() {
		$jQueryCheck = input::post('jQueryCheck');
		$param = input::escape(input::post('param'));
		$userRow = $this->db->select(userObj::getUsersTable(), 'id', array($jQueryCheck, $param), '', 1);
		die(($this->db->getNumRows() == 0) ? 'ok' : 'error');
	}
}