<?php
class index extends mainControler {

	function __construct() {
		parent::__construct();
		$this->view->setView('index');
	}

	public function index() {
		$this->view->setTitleTag('Home');
		$this->view->rander('index');
	}
}
