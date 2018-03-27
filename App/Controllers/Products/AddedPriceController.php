<?php
namespace Storemaker\App\Controllers\Products;
use Storemaker\System\Libraries;

class AddedPriceController extends Controller {

	function __construct() {
		parent::__construct();
		$this->loginOnly();
		$this->view->setView('added_price');
		$this->view->setCSS('added_price');
		$this->view->setJS('added_price');
	}

	public function index() {
		$this->view->setTitleTag('Adaos preturi');
		$this->view->rander('index', ['rows' => $this->model->get()]);
	}

	public function save($id=false)	{
		$min = input::post('min');
		$max = input::post('max');
		$precent = input::post('precent');
		$jsonReturn = ['status' => 'error', 'msg' => ''];

		if (strlen($min) == 0) {
			$jsonReturn['msg'] = 'N-ai completat pretul minim!';
		}
		if (strlen($max) == 0) {
			$jsonReturn['msg'] = 'N-ai completat pretul maxim!';
		}
		if (strlen($precent) == 0) {
			$jsonReturn['msg'] = 'N-ai completat procentul!';
		}
		if (!ctype_digit($min) || !ctype_digit($max) || !ctype_digit($precent)) {
			$jsonReturn['msg'] = 'Campurile pot contine doar caractere numerice';
		} else if ($min > $max) {
			$jsonReturn['msg'] = 'Pretul minim nu poate fi mai mare decat cel maxim!';
		}

		if (empty($jsonReturn['msg'])) {
			$action = ($id) ? $this->model->update($id, $min, $max, $precent) : $this->model->add($min, $max, $precent);

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
