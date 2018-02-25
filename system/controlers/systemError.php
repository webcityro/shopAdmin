<?php

class systemError extends mainControler {
	function __construct() {
		parent::__construct();
	}

	public function index($error) {
		$this->view->setView('error');
		$this->view->setTitleTag('Eroare!');
		$msg = array('errorMsg' => "<h3>Eroare!</h3>".$error);
		require_once config::get('path/sysViews').'tamplate/header_overall.php';
		$this->view->rander('index', array('errorMsg' => $error), false);
		require_once config::get('path/sysViews').'tamplate/footer_overall.php';
		exit();
	}
}