<?php
namespace Storemaker\App\Models\Suppliers\Feeds;
use Storemaker\System\Libraries;

class Feeds extends Model {
	protected $feedsTable;

	function __construct() {
		parent::__construct();
		$this->feedsTable = config::get('database/prefix').'feeds';
	}

	public function get() {
		$stx = $this->db->select($this->feedsTable, '*', '', 'supplierID ASC');
		return ($stx->getNumRows() > 0) ? (($stx->getNumRows() == 1) ? [$stx->results()] : $stx->results()) : false;
	}

	public function getByID($id) {
		$stx = $this->db->select($this->feedsTable, '*', $id, '', 1);
		return ($stx->getNumRows() == 1) ?  $stx->results() : false;
	}

	public function save($id, $feedFormName, $feedSuppliersID, $runAfterID, $loginType, $userName, $password, $feedLoginSoapDefineVars, $feedLoginSoapClientArgs, $feedLoginSoapLoginFunction, $feedLoginSoapResultsFunction, $userNameField, $passwordField, $loginURL, $mainURL, $type, $connectionStatus, $settings, $structure, $feedAntive) {
		$fieldsArr = [
			'supplierID' => $feedSuppliersID,
			'name' => $feedFormName,
			'loginType' => $loginType,
			'loginURL' => $loginURL,
			'loginUserName' => $userName,
			'loginPassword' => $password,
			'loginSoapVars' => $feedLoginSoapDefineVars,
			'loginSoapClientArgs' => $feedLoginSoapClientArgs,
			'loginSoapLoginFunction' => $feedLoginSoapLoginFunction,
			'loginSoapResultsFunction' => $feedLoginSoapResultsFunction,
			'loginCURLusernameField' => $userNameField,
			'loginCURLpasswordField' => $passwordField,
			'mainURL' => $mainURL,
			'type' => $type,
			'connectionStatus' => ($connectionStatus == 'true') ? '1' : '0',
			'settings' => $settings,
			'structure' => $structure,
			'active' => $feedAntive,
			'runAfterID' => $runAfterID,
		];

		if ($id == 0) {
			$query = $this->db->insert($this->feedsTable, $fieldsArr);
			$newID = $this->db->getLastInsertID();
		} else {
			$query = $this->db->update($this->feedsTable, $fieldsArr, $id, 1);
			$newID = $id;
		}

		return ($query) ? $newID : false;
	}

	public function makeFeedTempFile($content, $file=false) {
		$file = ($file) ?: 'feed_structure_'.time().'.txt';
		$filePath = config::get('path/temp').$file;
		$fileURL = config::get('site/domain').$file;

		if (file::make($filePath, $content)) {
			return $file;
		}
		return false;
	}

	public function getTempFile($file) {
		return file::get(config::get('path/temp').$file);
	}

	public function deleteFeedTempFile($file) {
		return file::remove(config::get('path/temp').$file);
	}

	public function delete($id)	{
		return $this->db->delete($this->feedsTable, $id);
	}
}