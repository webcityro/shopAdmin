<?php
namespace Storemaker\App\Controllers\System;
use Storemaker\System\Libraries\Controller;

class UnitsOfMeasurementController extends Controller {
	private $types;

	function construct() {
		$this->language->load('System/UnitsOfMeasurement');
		$this->setPaginationPath('/system/unitsOfMeasurement');

		$this->types = [
			'size' => $this->language->translate('size'),
			'digitalSize' => $this->language->translate('digitalSize'),
			'weight' => $this->language->translate('weight'),
			'temperature' => $this->language->translate('temperature'),
			'electric' => $this->language->translate('electric'),
			'fluids' => $this->language->translate('fluids'),
		];
	}

	public function index($request, $responce) {
		return $this->view->render($responce, 'system/unitsOfMeasurement.twig', ['types' => $this->types]);
	}
}
