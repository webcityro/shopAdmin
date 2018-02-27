<?php
namespace Storemaker\App\Controllers;
use Storemaker\System\Libraries;

class Error extends Controller {
	function __construct() {
		parent::__construct();
		$this->loginOnly();
		$this->view->setView('errors');
		$this->view->setCSS('errors');
		$this->view->setJS('errors');
	}

	public function index() {
		$this->view->setTitleTag('Erori');
		$this->view->rander('index', ['rows' => $this->model->get()]);
	}

	public function show($id) {
		$this->view->setTitleTag('Eroarea #'.$id);
		$this->view->rander('show', ['id' => $id, 'error' => $this->model->getByID($id)]);
	}

	public function delete($id, $redirect = false)	{
		$jsonReturn = ['status' => 'ok', 'msg' => ''];

		if (!$this->model->delete($id)) {
			$jsonReturn['msg'] = 'Nu s-a putut sterge momentan!';
		}

		if ($redirect) {
			$this->redirect('errorHandle');
		}
		die(json_encode($jsonReturn));
	}
}
