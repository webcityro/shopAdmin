<?php

class users extends mainControler {
	private $validate,
			  $groupsModel;

	function __construct() {
		parent::__construct();
		$this->loginOnly();
		$this->validate = new validate;
		$this->view->setView('users');
		$this->view->setCSS(['users']);
		$this->view->setJS('users');
		$this->view->setTitleTag(language::translate('pageTitle'));
	}

	public function index() {
		// $this->model->insertDummyUsers();
		$this->usersList = $this->model->getAll();
		$this->view->rander('index', [
			'usersList' => $this->usersList->results(),
			'pagination' => $this->usersList->getPagination()
		]);
	}

	public function addOrUpdate($id) {
		if (!token::check('singup', input::post('singupToken'))) {
			die(language::translate('ilegalPostTentative'));
		}

		$this->jsonResponse->setData('newToken', token::generate('singup'));

		if (!$this->checkValidation($id)) {
			$this->jsonResponse->setError($this->validate->getErrors());
		} else {
			if ($id != 0) {
				try {
					$user = new userObj($id);
					$data = [
						'fName' => input::post('fName'),
						'lName' => input::post('lName'),
						'email' => input::post('email'),
						'sex' => input::post('sex'),
						'active' => input::post('status'),
					];

					if ($id == config::get('ownerID') && !$this->thisUser->isOwner()) {
						throw new Exception(language::translate('canNotUpdate'));
					} else if (!$user->update($data)) {
						throw new Exception(language::translate('canNotUpdate'));
					}
				} catch (Exception $e) {
					$this->jsonResponse->setError($e->getMessage());
				}
			} else if ($insertUser = $this->model->insertUser()) {
				if ($this->model->createUserDir($insertUser)) {
					$this->jsonResponse->setData('id', $insertUser);
				} else {
					$user = new userObj($insertUser);
					$user->delete();
					$this->jsonResponse->setError(language::translate('canNotCreateUser'));
				}
			} else {
				$this->jsonResponse->setError(language::translate('canNotCreateUser'));
			}
		}

		$this->jsonResponse->getResponse();
	}

	private function checkValidation($userID) {
		$validateArr = [
			'fName' => [
				'label' => language::translate('formLabelFName'),
				'required' => 'true',
				'lengthRange' => array(3, 25),
				'alpha'
			],
			'lName' => [
				'label' => language::translate('formLabelLName'),
				'required' => 'false',
				'max' => 25,
				'alpha'
			],
			'password' => [
				'label' => language::translate('formLabelPassword'),
				'required' => 'true',
				'min' => 6
			],
			'email' => [
			 	'label' => language::translate('formLabelEmail'),
				'required' => 'true',
				'max' => 255,
				'email',
				'uniq' => ($userID == 0) ? userObj::getUsersTable() : ['table' => userObj::getUsersTable(), 'exclude' => ['id' => $userID]]
			],
			'sex' => [
				'label' => language::translate('formLabelSexValidation'),
				'required' => 'true'
			]
		];

		if ($userID == 0) {
			$validateArr['userName'] = [
				'label' => language::translate('formLabelUserName'),
				'required' => 'true',
				'lengthRange' => array(3, 25),
				'alnumCustom' => '_-.',
				'uniq' => userObj::getUsersTable()
			];
		}
		$this->validate->check($_POST, $validateArr);

		return $this->validate->passed();
	}

	public function checkExists($user) {
		echo '{exists:'.(($this->model->checkExists($user)) ? 'true' : 'false').'}';
		exit();
	}

	public function delete($userID)	{
		if (!token::check('delete', input::post('deleteToken'))) {
			die(language::translate('ilegalPostTentative'));
		}
		$jsonReturn = ['status' => 'error', 'msg' => ''];

		try {
			$user = new userObj($userID);

			if ($user->delete()) {
				$jsonReturn['status'] = 'ok';
				$jsonReturn['newToken'] = token::generate('delete');
			} else {
				throw new Exception(language::translate('canNotDelete'));
			}
		} catch (Exception $e) {
			$jsonReturn['msg'] = $e->getMessage();
		}
		die(json_encode($jsonReturn));
	}
}