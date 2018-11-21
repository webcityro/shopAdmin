<?php
namespace Storemaker\App\Controllers\Users;
use Storemaker\System\Libraries;

class LogoutController extends Controller {
	function __construct() {
		parent::__construct();
		$this->loginOnly();
	}

	public function index()	{
		$this->model->doLogOut();
		$this->redirect('index');
	}
}