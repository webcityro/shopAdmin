<?php

class loginModel extends mainModel {
	function __construct() {
		parent::__construct();
	}

	public function doLogin() {
		$jsonReturn = array();
		$jsonReturn['status'] = 'error';

		$userName = input::post('userName');
		$password = input::post('password');

		if (!empty($userName) && !empty($password)) {
			$user = new userObj();

			if ($user->find($userName, 'id, password, salt, email, active, singUpDate, inActiveDate')) {
				$userRow = $user->getData();

				if (hash::make($password, $userRow->salt) == $userRow->password) {
					token::delete('login');
					if ($userRow->active == 1) {
						$this->db->update(userObj::getUsersTable(), array('lastLogInDate' => date('Y-m-d H:i:s')), $userRow->id, 1);
						$rememberMe = input::post('rememberMe');

						if ($rememberMe == 'on') {
							$hashCheck = $this->db->select(userObj::getUsersSessionTable(), 'hash', array('userID', $userRow->id), 1);
							$hashCheck->getNumRows();

							if ($hashCheck->getNumRows() > 0) {
								$hash = $hashCheck->results()->hash;
							} else {
								$hash = substr(hash::uniqe(), 0, 64);
								$this->db->insert(userObj::getUsersSessionTable(), array('userID' => $userRow->id, 'hash' => $hash));
							}

							cookie::set('hash', $hash, 2592000);
						} else {
							session::set('userID', $userRow->id);
						}
						$jsonReturn['status'] = 'ok';
					} else {
						$singUpDate = current(explode(' ', $userRow->singUpDate));

						if ($userRow->inActiveDate == $singUpDate) {
							$jsonReturn['msg'] = language::translate('errorAccountNotActivated', $userRow->email);
							$jsonReturn['what'] = 'userName';
						} else {
							$jsonReturn['msg'] = ($userRow->inActiveDate == '9999-99-99') ?
								language::translate('errorAccountDisabledForever') :
								language::translate('errorAccountDisabled', $userRow->inActiveDate, $userRow->email);
							$jsonReturn['what'] = 'userName';
						}
					}
				} else {
					$jsonReturn['msg'] = language::translate('errorWrongPassword');
					$jsonReturn['what'] = 'password';
				}
			} else {
				$jsonReturn['what'] = 'userName';
				$jsonReturn['msg'] = language::translate('errorWrongUserName', $userName);
			}
		} else {
			$jsonReturn['msg'] = language::translate('errorNoInput');
			$jsonReturn['what'] = 'noInput';
		}

		$jsonReturn['redirect'] = config::get('site/domain').'index';
		echo json_encode($jsonReturn);
	}

	public function checkUserName()	{
		$userName = input::post('userName');
		try {
			$user = new userObj($userName);

			if ($user->exists()) {
				echo json_encode(['status' => 'ok', 'msg' => '']);
			} else {

			}
		} catch (Exception $e) {
			echo json_encode(['status' => 'error', 'msg' => language::translate('errorWrongUserName', $userName)]);
		}
		exit();
	}

	public function checkPassword()	{
		$userName = input::post('userName');
		try {
			$user = new userObj($userName);

			if ($user->exists()) {
				$userData = $user->getData();
				$password = hash::make(input::post('password'), $userData->salt);

				if ($userData->password == $password) {
					$jsonReturn['status'] = 'ok';
				} else {
					$jsonReturn['status'] = 'error';
					$jsonReturn['msg'] = language::translate('errorWrongPassword');
				}
				echo json_encode($jsonReturn);
			} else {

			}
		} catch (Exception $e) {
			echo json_encode(['status' => 'error', 'msg' => language::translate('errorWrongUserName', $userName)]);
		}
		exit();
	}
}