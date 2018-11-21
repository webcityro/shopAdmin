<?php
class singup extends mainControler {
	private $validate;

	function __construct() {
		parent::__construct();
		$this->validate = new validate;
		$this->logOutOnle();
		/*$this->view->setView('register');
		$this->view->setTitleTag(language::translate('pageTitle'));
		$this->view->setPublicCSS('form');
		$this->view->setCSS('register');
		$this->view->setJS('register_functions');*/
	}

	public function index() {
		// $this->model->showEmailUser();
		// $this->view->rander('index');
	}

	public function run() {
		if (!token::check('singup', input::post('singupToken'))) {
			die('Tentativa de post ilegal');
		}

		$viewVars = array('status' => 'success', 'errors' => '', 'msg' => '');

		$this->validate->check($_POST, array('fName'    => array('label' => language::translate('formLabelFName'),
													    		  'required' => 'true',
													    		  'lengthRange' => array(3, 25),
													    		  'alpha'),
											'lName'    => array('label' => language::translate('formLabelLName'),
													    		  'required' => 'false',
													    		  'max' => 25,
													    		  'alpha'),
											'userName'  => array('label' => language::translate('formLabelUserName'),
													    		  'required' => 'true',
													    		  'lengthRange' => array(3, 25),
													    		  'alnumCustom' => '_-.',
													    		  'uniq' => 'users'),
											 'password'  => array('label' => language::translate('formLabelPassword'),
															      'required' => 'true',
													    		  'min' => 6),
											 'password2' => array('label' => language::translate('formLabelPasswords'),
													    		  'required' => 'true',
													    		  'match' => 'password'),
											 'email' 	 => array('label' => language::translate('formLabelEmail'),
															  	  'required' => 'true',
															  	  'max' => 255,
															  	  'email',
															  	  'uniq' => 'users'),
											 'sex'		 => array('label' => language::translate('formLabelSexValidation'),
															   	  'required' => 'true')));

		if (!$this->validate->passed()) {
			$viewVars['errors'] = $this->validate->getErrors();
			$viewVars['status'] = 'error';
		} else {
			$insertUser = $this->model->insertUser();

			if ($insertUser !== false) {
				if ($this->model->createUserDir($insertUser) && $this->model->emailUser($insertUser)) {
					die('merge');
					$viewVars['status'] = 'success';
					$viewVars['msg'] = language::translate('registractionSuccess', input::post('email'));
				} else {
					$viewVars['status'] = 'error';
					$user = new userObj($insertUser);
					$user->delete();
					$viewVars['msg'] = language::translate('registractionFail');
				}
			}

		}

		echo json_encode($viewVars);
		exit();
	}

	public function checkExists($user) {
		echo ($this->model->checkExists($user)) ? 'error' : 'ok';
		exit();
	}

	public function activate($id, $code) {
		$msg = $this->model->activateUser($id, $code);
		session::flash('home', $msg);
		$this->redirect();
	}
}
