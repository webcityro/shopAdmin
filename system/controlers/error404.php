<?php

class error404 extends mainControler {
	function __construct() {
		parent::__construct();
	}

	public function index($msg) {
		$this->view->setView('404');
		$this->view->setTitleTag('Eroare 404');
		$msg = array('msg' => '<h3>Eroare 404</h3>'.$msg);
		require_once config::get('path/sysViews').'tamplate/header_overall.php';
		$this->view->rander('index', $msg, false);
		require_once config::get('path/sysViews').'tamplate/footer_overall.php';
		exit();
	}
}