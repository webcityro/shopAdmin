<?php
namespace Storemaker\System\Libraries;

use Storemaker\App\Libraries\User;

class Controller {
	protected $model,
				 $view,
				 $thisUser,
				 $jsonResponse;

	function __construct() {
		$this->view = new View();
		$this->thisUser = User::init();
		$this->jsonResponse = new JsonResponse();
	}

	public function setModel($model) {
		if (class_exists($model)) {
			$this->model = new $model();
		}
	}

	protected function redirect($value = '') {
		header('Location: '.Config::get('site/domain').$value);
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