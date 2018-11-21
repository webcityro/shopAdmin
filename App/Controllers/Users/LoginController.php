<?php
namespace Storemaker\App\Controllers\Users;
use Storemaker\System\Libraries;

class LoginController extends Controller {
	function __construct() {
		parent::__construct();
		$this->logOutOnle();
		/*$this->view->setView('login');
		$this->view->setPublicCSS('form');
		$this->view->setCSS('login');
		$this->view->setJS('login_functions');
		$this->view->setTitleTag('Log in');*/
	}

	public function index() {
		// $this->view->rander('index');
	}

	public function run() {
		if (!token::check('login', input::post('loginToken'), true)) {
			die('{"staus": "error", "msg": "Tentativa de post ilegal"}');
		}

		$error = $this->model->doLogin();
	}

	public function checkUserName()	{
		if (!token::check('login', input::post('loginToken'), true)) {
			die('{"staus": "error", "msg": "Tentativa de post ilegal"}');
		}

		$this->model->checkUserName();
	}

	public function checkPassword()	{
		if (!token::check('login', input::post('loginToken'), true)) {
			die('{"staus": "error", "msg": "Tentativa de post ilegal"}');
		}

		$this->model->checkPassword();
	}
}