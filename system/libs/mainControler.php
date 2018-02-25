<?php

class mainControler {
	private $argumentsToIndex,
			  $core;
	protected $model,
				 $view,
				 $thisUser,
				 $jsonResponse;

	function __construct() {
		$this->core = core::init();
		$this->view = new view();
		$this->argumentsToIndex = false;
		$this->thisUser = userObj::init();
		$this->jsonResponse = new jsonResponse();
	}

	public function loadModel($name) {
		$modelName = $name.'Model';
		if (class_exists($modelName)) {
			$this->model = new $modelName();
		}
	}

	protected function setArgsToIndex($value) {
		$this->argumentsToIndex = $value;
	}

	public function getArgsToIndex() {
		return $this->argumentsToIndex;
	}

	protected function redirect($value = '') {
		header('Location: '.config::get('site/domain').$value);
	}

	protected function loginOnly($redirect = 'index') {
		if (!$this->thisUser->isLogIn()) {
			$this->redirect($redirect);
		}
	}

	protected function logOutOnle($redirect = 'index') {
		if ($this->thisUser->isLogIn()) {
			$this->redirect($redirect);
		}
	}
}