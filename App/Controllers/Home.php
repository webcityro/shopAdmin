<?php
namespace Storemaker\App\Controllers;
use Storemaker\System\Libraries;

class Home extends Controller {

	function __construct() {
		parent::__construct();
		$this->view->setView('index');
	}

	public function index() {
		$this->view->setTitleTag('Home');
		$this->view->rander('index');
	}
}
