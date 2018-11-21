<?php
namespace Storemaker\App\Controllers;
use Storemaker\System\Libraries;

class Ajax extends Controller {

	function __construct() {
		parent::__construct();
	}

	public function jsGetInit() {
		$jsonReturn = ['domain' => config::get('site/domain'),
						'style' => config::get('site/style').'/',
						'errorIcon' => config::get('icon/error'),
						'okIcon' => config::get('icon/ok'),
						'loaderIcon' => config::get('icon/loader'),
						'tempURL' => config::get('url/temp')];
		die(json_encode($jsonReturn));
	}

	public function autosuggest() {
		$table = input::post('table');
		$column = input::post('column');
		$columnID = input::post('columnID');
		$value = input::post('value');
		$jsonReturn = ['status' => 'ok', 'msg' => ''];

		if (($rows = $this->model->getAutosuggest($table, $column, $columnID, $value)) !== false) {
			$jsonReturn['rows'] = $rows;
		} else {
			$jsonReturn['status'] = 'error';
			$jsonReturn['msg'] = 'Nu s-a putut cauta autosuggest-ul!';
		}

		die(json_encode($jsonReturn));
	}
}
