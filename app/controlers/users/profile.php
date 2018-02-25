<?php

class profile extends mainControler {
	private $user;
	private $userObj;
	private $isThisUser;
	private $thisUserData;
	private $validate;

	function __construct() {
		parent::__construct();
		$this->loginOnly();
		$this->setArgsToIndex(true);
		$this->view->setView('users');
		$this->view->setCSS('profile');
		$this->view->setJS('profile');
		$this->thisUserData = $this->thisUser->getData();
		$this->validate = new validate();
	}

	public function index2($user = false) {
		if ($user === false) {
			if ($this->thisUser->isLogIn()) {
				$this->userObj = $this->thisUser;
				$this->user = $this->thisUserData;
				$this->view->rander('profile', array('userData' => $this->user, 'isThisUser' => true));
			} else {
				$this->redirect('index');
			}
		} else {
			$args = func_get_args();
			// echo "<pre>".print_r($args, 1);
			$this->setUser($args[0]);

			if (count($args) > 1) {
				$this->callMethod($args);
			} else {
				$this->view->rander('profile', array('userData' => $this->user, 'isThisUser' => $this->isThisUser));
			}
		}
	}

	private function setUser($user)	{
		try {
			$this->userObj = new userObj($user);
			$this->user = $this->userObj->getData();
			$this->isThisUser = ($this->thisUserData->id == $this->user->id) ? true : false;
			$this->view->setTitleTag(language::translate('pageTitle', $this->user->userName));
		} catch (Exception $e) {
			errors::systemError($e->getMessage());
		}
	}

	private function edit()	{
		if ($this->isThisUser) {
			$this->view->setTitleTag(language::translate('editProfilInfo'));
			$this->view->rander('edit', array('userData' => $this->user));
		} else {
			errors::systemError(language::translate('cantEditAnotherUsersProfile'));
		}
	}

	private function callMethod($args) {
		unset($args[0]);
		$args = array_values($args);

		if (count($args) == 1) {
			$this->$args[0]();
		} else if (count($args) > 1) {
			$method = $args[0];
			unset($args[0]);
			call_user_func_array([$this, $method], $args);
		}
	}

	public function checkEmailExist() {
		$json = ['status' => 'ok'];

		if ($this->model->checkEmailExists($this->user->id, input::post('email'))) {
			$json['status'] = 'usedEmail';
		}
		die(json_encode($json));
	}

	public function update() {
		$this->checkToken();
		$json = ['status' => 'ok', 'error' => '', 'newToken' => token::generate('update')];

		try {
			$this->thisUser->update([input::post('field') => input::post('value')]);
		} catch (Exception $e) {
			$json['status'] = 'error';
			$json['msg'] = 'Nu s-a putut aptualiza momenta, incearca mai tarziu!';
			$json['debug'] = $e->getMessage();
		}

		die(json_encode($json));
	}

	public function changePassword() {
		$this->checkToken();
		$json = ['status' => 'ok', 'error' => '', 'newToken' => token::generate('update')];
		$oldPassword = input::post('oldPassword');
		$newPassword = input::post('newPassword');
		$confirmPassword = input::post('confirmPassword');

		if (hash::make($oldPassword, $this->thisUserData->salt) == $this->thisUserData->password) {
			if ($this->validateChandePassword()) {
				try {
					$newSalt = hash::salt();
					$this->thisUser->update(['password' => hash::make($newPassword, $newSalt), 'salt' => $newSalt]);
				} catch (Exception $e) {
					$json['status'] = 'error';
					$json['msg'] = 'Nu s-a putut aptualiza momenta, incearca mai tarziu!';
					$json['debug'] = $e->getMessage();
				}
			} else {
				$json['status'] = 'error';
				$json['msg'] = $this->validate->getErrors();
			}
		}
		die(json_encode($json));
	}

	private function checkToken() {
		if (!token::check('update', input::post('updateToken'))) {
			die('Tentativa de post ilegal');
		}
	}

	private function validateChandePassword() {
		$this->validate->check($_POST, [
			'newPassword'  => ['label' => language::translate('formLabelNewPassword'),
							   'required' => 'true',
						       'min' => 6],
			'confirmPassword' => ['label' => language::translate('formLabelConfirmNewPassword'),
						    	  'required' => 'true',
						    	  'match' => 'newPassword']]);

		return $this->validate->passed();
	}
}