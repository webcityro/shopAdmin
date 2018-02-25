<?php
use \Curl\Curl;
use \feeds\logins;

class feeds extends mainControler {
	private $supliers,
			$parserTypes = [],
			$loginTypes = [],
			$feedLogin,
			$jsonReturn = ['status' => 'ok', 'msg' => ''],
			$feedTypes = ['SimpleXMLElement', 'stdClass'];

	function __construct($checkLogin = true) {
		parent::__construct();

		if ($checkLogin) {
			$this->loginOnly();
		}

		$this->view->setView('feeds');
		$this->view->setCSS('feeds');
		$this->view->setJS(['feeds', 'papaparse.min', 'jszip', 'xlsx.full.min', 'parseJSON', 'parseXML', 'parseCSV', 'parseExcel', 'parseExplode']);

		$this->suppliersModel = new suppliersModel();
		$this->getLoginTypes();
		$this->getParserTypes();
	}

	public function index() {
		// die('<pre>'.print_r($this->types, 1));
		$rows = $this->model->get();
		$feedsListBySupplyers = [];

		foreach ($rows as $row) {
			$feedsListBySupplyers[$row->supplierID] = (isset($feedsListBySupplyers[$row->supplierID])) ? $feedsListBySupplyers[$row->supplierID] : [];
			$feedsListBySupplyers[$row->supplierID][] = $row;
		}

		$this->view->setTitleTag('Feed-uri');
		$this->view->rander('index', ['rows' => $rows,
									  'suppliers' => $this->suppliersModel->get(),
									  'parserTypes' => $this->parserTypes,
									  'loginTypes' => $this->loginTypes,
									  'feedsListBySupplyers' => $feedsListBySupplyers]);
	}

	private function getParserTypes() {
		$dir = dir::read(config::get('path/appLibs').'feeds/parsers');

		foreach ($dir as $d) {
			if ($d == '.' || $d == '..' || $d == 'parser.php') {
				continue;
			}
			$class = '\\feeds\\parsers\\'.str_replace('.php', '', $d);
			$parser = new $class('getName', '');
			$this->parserTypes[] = $parser->type;
		}
	}

	private function getLoginTypes() {
		$dir = dir::read(config::get('path/appLibs').'feeds/logins');

		foreach ($dir as $d) {
			if ($d == '.' || $d == '..') {
				continue;
			}
			$class = '\\feeds\\logins\\'.str_replace('.php', '', $d);
			$login = new $class();
			$this->loginTypes[] = $login->type;
		}
	}

	public function testConnection($feedFile=false) {
		$type = input::post('type');
		$userName = input::post('userName');
		$password = input::post('password');
		$userNameField = input::post('userNameField');
		$passwordField = input::post('passwordField');
		$soapDefineVars = input::post('feedLoginSoapDefineVars');
		$soapClientArgs = input::post('feedLoginSoapClientArgs');
		$soapLoginFunction = input::post('feedLoginSoapLoginFunction');
		$soapResultsFunction = input::post('feedLoginSoapResultsFunction');
		$url = input::post('url');
		$mainURL = input::post('mainURL');

		$login = $this->login($type, $userName, $password, $userNameField, $passwordField, $soapDefineVars, $soapClientArgs, $soapLoginFunction, $soapResultsFunction, $url, $mainURL);

		if ($login !== false) {
			if ($this->isXML($login)) {
				$login = $login->asXML();
			}

			$this->jsonReturn['content'] = $login;

			if ($file = $this->model->makeFeedTempFile((is_array($login) || is_object($login)) ? json_encode($login) : $login, $feedFile)) {
				$this->jsonReturn['file'] = $file;
				$this->jsonReturn['content'] = !$this->isExcel($file) ? $login : '';
			} else {
				$this->jsonReturn['content'] = $login;
				$this->jsonReturn['msg'] = 'Nu s-a putut creia fisierul temporal continand structura feedului!';
			}
		} else {
			$this->jsonReturn['msg'] = 'Logare nereusita!';
		}

		$this->jsonReturn['content'] = utf8_encode($this->jsonReturn['content']);

		die(json_encode($this->jsonReturn));
	}

	public function login($type, $userName, $password, $userNameField, $passwordField, $soapDefineVars, $soapClientArgs, $soapLoginFunction, $soapResultsFunction, $url, $mainURL) {
		if ($type == 'none') {
			return file::get($mainURL);
		}

		if (!$this->getLoginClass($type)) {
			$this->jsonReturn['msg'] = 'Nu s-a gasit clasa de logare!';
			$this->jsonReturn['status'] = 'error';
			return false;
		}
		$this->feedLogin->setLoginURL($url);
		$this->feedLogin->setMainURL($mainURL);

		if ($type == 'CURL') {
			$content = $this->feedLogin->run($userName, $password, $userNameField, $passwordField);
		} else if ($type == 'Soap') {
			$this->feedLogin->setDefineVars($soapDefineVars);
			$this->feedLogin->setClientArgs($soapClientArgs);
			$this->feedLogin->setloginMethod($soapLoginFunction);
			$this->feedLogin->setResultsMethod($soapResultsFunction);
			$content = $this->feedLogin->run();
		} else if ($type == 'NOD') {
			$content = $this->feedLogin->run($userName, $password);
		} else {
			$content = $this->feedLogin->run($userName, $password);
		}
		return $content;
	}

	private function getLoginClass($type) {
		$class = '\\feeds\\logins\\login'.$type;

		if (class_exists($class)) {
			$this->feedLogin = new $class();
			return true;
		}
		return false;
	}

	public function getForEdit($id)	{
		if ($rows = $this->model->getByID($id)) {
			$this->jsonReturn['row'] = $rows;

			if ($rows->connectionStatus == '1') {
				$login = $this->login($rows->loginType,
					$rows->loginUserName,
					$rows->loginPassword,
					$rows->loginCURLusernameField,
					$rows->loginCURLpasswordField,
					$rows->loginSoapVars,
					$rows->loginSoapClientArgs,
					$rows->loginSoapLoginFunction,
					$rows->loginSoapResultsFunction,
					$rows->loginURL,
					$rows->mainURL);

				if ($login !== false) {
					if ($this->isXML($login)) {
						$login = $login->asXML();
					}

					if ($file = $this->model->makeFeedTempFile((is_array($login) || is_object($login)) ? json_encode($login) : $login)) {
						$this->jsonReturn['file'] = $file;
						$this->jsonReturn['content'] = !$this->isExcel($file) ? $login : '';
					} else {
						$this->jsonReturn['msg'] = 'Nu s-a putut creia fisierul temporal continand structura feedului!';
					}
				} else {
					$this->jsonReturn['msg'] = 'Nu s-a reusit logarea la feed, este posibil ca datele de logare sa fi fost schimbate!';
				}
			}
		} else {
			$this->jsonReturn['status'] = 'error';
			$this->jsonReturn['msg'] = 'Nu s-a gasit Feed-ul!';
		}
		die(json_encode($this->jsonReturn, JSON_HEX_TAG));
	}

	public function save($id)	{
		$jsonReturn = ['status' => 'error', 'msg' => ''];
		$feedFormName = input::post('feedFormName');
		$feedSuppliersID = input::post('feedSuppliersID');
		$runAfterID = input::post('runAfterID');
		$loginType = input::post('loginType');
		$userName = input::post('userName');
		$password = input::post('password');
		$feedLoginSoapDefineVars = input::post('feedLoginSoapDefineVars');
		$feedLoginSoapClientArgs = input::post('feedLoginSoapClientArgs');
		$feedLoginSoapLoginFunction = input::post('feedLoginSoapLoginFunction');
		$feedLoginSoapResultsFunction = input::post('feedLoginSoapResultsFunction');
		$userNameField = input::post('userNameField');
		$passwordField = input::post('passwordField');
		$loginURL = input::post('feedLoginURL');
		$mainURL = input::post('mainURL');
		$feedActive = input::post('feedActive');
		$type = input::post('type');
		$connectionStatus = input::post('connectionStatus');
		$tempFile = input::post('tempFile');
		$structure = input::post('structure');
		$settings = input::post('settings');

		if (empty($feedFormName) || empty($feedSuppliersID) || empty($type)) {
			$jsonReturn['msg'] = 'Campurile marcate cu (*) sunt obligatorii!';
		} else if (empty($loginURL) && empty($mainURL)) {
			$jsonReturn['msg'] = 'N-ai completat nici una dintre adresele URL!';
		} else if ($loginType == 'none' && empty($mainURL)) {
			$jsonReturn['msg'] = 'Nu ai completat URL-ul principal!';
		} else if (($loginType == 'url' || $loginType == 'curl') && (empty($userName) || empty($password))) {
			$jsonReturn['msg'] = 'Nu ai completat numele de utilizator sau parola!';
		} else if (($loginType == 'curl') && (empty($userName) || empty($password))) {
			$jsonReturn['msg'] = 'Nu ai completat Key post curl nume de utilizator / parola!';
		} else if ($newID = $this->model->save($id, $feedFormName, $feedSuppliersID, $runAfterID, $loginType, $userName, $password, $feedLoginSoapDefineVars, $feedLoginSoapClientArgs, $feedLoginSoapLoginFunction, $feedLoginSoapResultsFunction, $userNameField, $passwordField, $loginURL, $mainURL, $type, $connectionStatus, $settings, $structure, $feedActive)) {
			$jsonReturn['status'] = 'ok';
			$jsonReturn['id'] = $newID;

			if (!empty($tempFile) && ($msg = $this->deleteTempFile($tempFile, false)) !== true) {
				$jsonReturn['msg'] = $msg;
			}
		} else {
			$jsonReturn['msg'] = 'Feed-ul nu s-a putut salva momentan!';
		}

		die(json_encode($jsonReturn));
	}

	public function delete($id, $deleteProducts)	{
		$jsonReturn = ['status' => 'ok', 'msg' => ''];

		if ($deleteProducts == 'true') {
			// delete produsele dupa id-ul de feed
		}

		if (!$this->model->delete($id)) {
			$jsonReturn['msg'] = 'Nu s-a putut sterge momentan!';
			$jsonReturn['status'] = 'error';
		}
		die(json_encode($jsonReturn));
	}

	public function deleteTempFile($file, $die=true) {
		$jsonReturn = ['status' => 'ok', 'msg' => ''];

		if (!$this->model->deleteFeedTempFile($file)) {
			$jsonReturn['msg'] = 'Nu s-a putut sterge fisierul temporal continand structura feedului!';
			$jsonReturn['status'] = 'error';

			if (!$die) {
				return ($jsonReturn['status'] == 'ok') ? true : $jsonReturn['msg'];
			}
		}
		die(json_encode($jsonReturn));
	}

	public function getTempFile($file) {
		$jsonReturn = ['status' => 'ok', 'msg' => ''];

		if ($content = $this->model->getTempFile($file)) {
			$jsonReturn['content'] = $content;
		} else {
			$jsonReturn['msg'] = 'Nu s-a gasit fisierul temporal continand structura feedului!';
			$jsonReturn['status'] = 'error';
		}
		die(json_encode($jsonReturn));
	}

	protected function getExt($type) {
		switch (strtolower($type)) {
			case 'json':
				return 'json';
				break;
			case 'xml':
				return 'xml';
				break;
			case 'csv':
				return 'csv';
				break;
			case 'excel':
				return 'xls';
				break;
			case 'explode':
				return 'txt';
				break;

			default:
				return 'txt';
				break;
		}
	}

	private function isXML($value) {
		return is_a($value, 'SimpleXMLElement');
	}

	private function isExcel($file)	{
		// die(config::get('path/temp').$file);
		$type = PHPExcel_IOFactory::identify(config::get('path/temp').$file);
		$types = ['Excel5', 'Excel2007', 'Excel2003XML', 'OOCalc', 'SYLK', 'Gnumeric'];
		return in_array($type, $types);
	}
}
