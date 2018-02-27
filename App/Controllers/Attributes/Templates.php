<?php
namespace Storemaker\App\Controllers\Attributes;
use Storemaker\System\Libraries;

class Templates extends Controller {

	function __construct() {
		parent::__construct();
		$this->loginOnly();
		$this->view->setTitleTag('Panou de admin / Sabloane');
		$this->view->setCSS('sabloane');
		$this->view->setPublicJS('viewCategoryes');
		$this->view->setJS('sabloane');
		$this->view->setView('sabloane');
	}

	public function index() {
		// $this->model->getSablonsCount(4);
		$this->view->rander('index', ['sabloansTree' => $this->model->getSablonsTree()]);
	}

	public function edit($id) {
		$this->view->rander('edit', ['sablonID' => $id,
									 'sabloansTree' => $this->model->getSablonsTree(),
									 'sablonName' => $this->model->getSablonName($id),
									 'sablonGroups' => $this->model->getSablonGroups($id)]);
	}

	public function addOrEditSablon($ID, $action) {
		$name = input::post('name');
		$jsonReturn = ['status' => 'ok', 'msg' => ''];

		if (!empty($name) && strlen($name) <= 255) {
			$run = ($action == 'insert') ? $this->model->addSablon($name, $ID) : $this->model->updateSablon($name, $ID);
			if ($id = $run) {
				$jsonReturn['id'] = $id;
			} else {
				$jsonReturn['status'] = 'error';
				$jsonReturn['msg'] = ($action == 'insert') ? 'Sablonul nu s-a putut adauga!' : 'Numele sablonului nu s-a putut schimba!';
			}
		} else {
			$jsonReturn['status'] = 'error';
			$jsonReturn['msg'] = 'Numele sablonului trebuie sa aiba intre 1 si 50 caractere!';
		}
		die(json_encode($jsonReturn));
	}

	public function addOrUpdateGroup($ID, $action, $sablonID = 0, $editAll = false) {
		$name = input::post('name');
		$sort = input::post('sort');
		$jsonReturn = ['status' => 'error', 'msg' => ''];

		if (!empty($name) && strlen($name) <= 255) {
			$run = ($action == 'insert') ? $this->model->addGroup($name, $sort, $ID) : $this->model->updateGroup($name, $ID, $sablonID, $editAll);
			if ($run == 'exists') {
				$jsonReturn['msg'] = 'Grupul '.$name.' exista deja in accest sablob!';
			} else if ($id = $run) {
				$jsonReturn['status'] = 'ok';
				$jsonReturn['id'] = $id;
			} else {
				$jsonReturn['msg'] = 'Grupul nu s-a putut adauga!';
			}
		} else {
			$jsonReturn['msg'] = 'Numele grupului trebuie sa aiba intre 1 si 50 caractere!';
		}
		die(json_encode($jsonReturn));
	}

	public function insertOreditAttributes($sablonID, $id=false, $editAll='false', $changeName='true') {
		$name = input::post('name');
		$um = input::post('um');
		$desc = input::post('desc');
		$info = input::post('info');
		$hideLabel = input::post('hideLabel');
		$hide = input::post('hide');
		$sort = input::post('sort');
		$groupID = input::post('groupID');
		$jsonReturn = ['status' => 'error', 'msg' => ''];

		if(!empty($name) && strlen($name)<=50){
			$run = (!$id) ? $this->model->addAttributes($name, $um, $desc, $info, $hideLabel, $hide, $sort, $sablonID, $groupID) :
							$this->model->updareAttributes($name, $um, $desc, $info, $hideLabel, $hide, $sablonID, $groupID, $id, $editAll, $changeName);

			if ($run == 'belongsToOtherGroup') {
				$jsonReturn['msg'] = 'Atributul '.$name.' apartine deja altui grup!';
			} else if ($id = $run) {
				$jsonReturn['status'] = 'ok';
				$jsonReturn['id'] = $id;
			} else {
				$jsonReturn['msg'] = 'Grupul nu s-a putut adauga!';
			}
		} else {
			$jsonReturn['msg'] = 'Numele carateristicii trebuie sa aiba intre 1 si 50 caractere!';
		}
		die(json_encode($jsonReturn));
	}

	public function sort($type, $sort, $switchSort, $ID, $switchID, $sablonID, $count)	{
		$jsonReturn = ['status' => 'ok', 'msg' => ''];
		$update = $this->model->sort($type, $sort, $switchSort, $ID, $switchID, $sablonID, $count);

		if (!$update) {
			$jsonReturn['status'] = 'error';
			$jsonReturn['msg'] = 'Nu s-a putut muta!';
		}
		die(json_encode($jsonReturn));
	}

	public function delete($what, $sablonID, $id, $deleteAll = 'false')	{
		$jsonReturn = ['status' => 'ok', 'msg' => ''];

		switch ($what) {
			case 'attribute':
				if ($deleteAll == "true") {
					$run = ($this->model->deleteAttributeFromOCTables($id) && $this->model->getProductsModel()->deleteAttributes($id));
				} else {
					$run = $this->model->deleteAttributeFromProductsBySablon($id, $sablonID);
				}
				$run = ($run && $this->model->deleteAttributeFromSablon($id, $sablonID));
				break;

			case 'group':
				$run = ($this->model->deleteAttributesByGroup($id, $sablonID) &&
						$this->model->deleteGroup($sablonID, $id) &&
						$this->model->getProductsModel()->deleteAttributesByGroup($id, $sablonID));
				break;

			case 'sablon':
				$run = ($this->model->deleteGroupsBySablon($sablonID) &&
						$this->model->deleteAttributesBySablon($sablonID) &&
						$this->model->deleteSablon($sablonID));

				if ($deleteAll == 'true') {
					$run = ($run && $this->model->deleteProductsBySablon($sablonID));
				}
				break;
		}
		if (!$run) {
			$jsonReturn = ['status' => 'error', 'msg' => 'Nu s-a putut sterge acum!'];
		}
		die(json_encode($jsonReturn));
	}
}