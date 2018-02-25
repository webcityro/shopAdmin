<?php
class aliases extends mainControler {
	private $suppliers,
			$types = [
				'productLink' => 'Link produs',
				'category' => 'Categorie',
				'attributeName' => 'Denumire atribut',
				'attributeValue' => 'Valoare atribut',
				'id' => 'ID produs',
				'name' => 'Nume',
				'model' => 'Model',
				'upc' => 'Cod produs',
				'manufacturer' => 'Fabricant',
			];

	function __construct() {
		parent::__construct();
		$this->loginOnly();
		$this->suppliers = new suppliersModel();
		$this->view->setPublicCSS('autosuggest');
		$this->view->setPublicJS('autosuggest');
		$this->view->setView('aliases');
		$this->view->setCSS('aliases');
		$this->view->setJS('aliases');
	}

	public function index() {
		$this->view->setTitleTag('Alias-uri');
		$this->view->rander('index', [
			'rows' => $this->model->get(),
			'suppliers' => $this->suppliers->get(),
			'types' => $this->types,
			'typeToTable' => $this->model->getTypeToTable()
		]);
	}

	public function save($id)	{
		$type = input::post('type');
		$supplierID = input::post('supplierID');
		$manufacturerID = input::post('manufacturerID');
		$itemID = input::post('itemID');
		$search = input::post('search');
		$array = input::post('array');
		$prefix = input::post('prefix');
		$replaceWith = input::post('replaceWith');
		$active = input::post('active');
		$jsonReturn = ['status' => 'error', 'msg' => ''];

		if (strlen($search) == 0) {
			$jsonReturn['msg'] = 'N-ai completat campul "cautare"!';
		} else if (($id = $this->model->save($id, $type, $supplierID, $manufacturerID, $itemID, $search, $array, $prefix, $replaceWith, $active)) !== false) {
			$jsonReturn['id'] = $id;
			$jsonReturn['status'] = 'ok';
		} else {
			$jsonReturn['msg'] = (($id > 0) ? 'Modificarea' : 'Adaugarea').' nu s-a putut efectua momentan!';
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
