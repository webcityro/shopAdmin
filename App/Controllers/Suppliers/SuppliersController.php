<?php
namespace Storemaker\App\Controllers\Suppliers;
use Storemaker\System\Libraries;

class SuppliersController extends Controller {

	function __construct() {
		parent::__construct();
		$this->loginOnly();
		$this->view->setView('suppliers');
		$this->view->setCSS('suppliers');
		$this->view->setJS('suppliers');
	}

	public function index() {
		$this->view->setTitleTag('Furnizori');
		$this->view->rander('index', ['rows' => $this->model->get()]);
	}

	public function getForEdit($id)	{
		$jsonReturn = ['status' => 'ok', 'msg' => ''];

		if ($rows = $this->model->getByID($id)) {
			$jsonReturn['row'] = $rows;
		} else {
			$jsonReturn['status'] = 'error';
			$jsonReturn['msg'] = 'Nu s-a gasit furnizorul!';
		}
		die(json_encode($jsonReturn));
	}

	public function save($id=false)	{
		$name = input::post('name');
		$site = input::post('site');
		$contactName = input::post('contactName');
		$phone1 = input::post('phone1');
		$phone2 = input::post('phone2');
		$phone3 = input::post('phone3');
		$fax = input::post('fax');
		$email = input::post('email');
		$jsonReturn = ['status' => 'error', 'msg' => ''];

		if (strlen($name) == 0 || strlen($site) == 0) {
			$jsonReturn['msg'] = 'Campurile cu steluta(*) sunt obligatorii!';
		} else {
			$action = ($id) ? $this->model->update($id, $name, $site, $contactName, $phone1, $phone2, $phone3, $fax, $email)
							: $this->model->add($name, $site, $contactName, $phone1, $phone2, $phone3, $fax, $email);

			if ($action) {
				$jsonReturn['id'] = $action;
				$jsonReturn['status'] = 'ok';
			} else {
				$jsonReturn['msg'] = (($id) ? 'Modificarea' : 'Adaugarea').' nu s-a putut efectua momentan!';
			}
		}
		die(json_encode($jsonReturn));
	}

	public function delete($id)	{
		$jsonReturn = ['status' => 'ok', 'msg' => ''];

		if (!$this->model->delete($id)) {
			$jsonReturn['msg'] = 'Nu s-a putut sterge momentan!';
		}
		die(json_encode($jsonReturn));
	}
}
