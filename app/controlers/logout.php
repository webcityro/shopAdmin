<?php
class logout extends mainControler {
	function __construct() {
		parent::__construct();
		$this->loginOnly();
	}

	public function index()	{
		$this->model->doLogOut();
		$this->redirect('index');
	}
}