<?php
namespace Storemaker\App\Models\Suppliers;
use Storemaker\System\Libraries;

class Suppliers extends Model {
	private $suppliersTable;

	function __construct() {
		parent::__construct();
		$this->suppliersTable = config::get('database/prefix').'suppliers';
	}

	public function get() {
		$stx = $this->db->select($this->suppliersTable, '*');
		return ($stx->getNumRows() > 0) ? (($stx->getNumRows() == 1) ? [$stx->results()] : $stx->results()) : false;
	}

	public function getByID($id) {
		$stx = $this->db->select($this->suppliersTable, '*', $id, '', 1);
		return ($stx->getNumRows() == 1) ?  $stx->results() : false;
	}

	public function add($name, $site, $contactName, $phone1, $phone2, $phone3, $fax, $email) {
		return ($this->db->insert($this->suppliersTable, [
			'name' => $name,
			'site' => $site,
			'contactName' => $contactName,
			'phone1' => $phone1,
			'phone2' => $phone2,
			'phone3' => $phone3,
			'fax' => $fax,
			'email' => $email
			])) ? $this->db->getLastInsertID() : false;
	}

	public function update($id, $name, $site, $contactName, $phone1, $phone2, $phone3, $fax, $email) {
		return ($this->db->update($this->suppliersTable, [
			'name' => $name,
			'site' => $site,
			'contactName' => $contactName,
			'phone1' => $phone1,
			'phone2' => $phone2,
			'phone3' => $phone3,
			'fax' => $fax,
			'email' => $email
			], $id)) ? $id : false;
	}

	public function delete($id)	{
		return $this->db->delete($this->suppliersTable, $id);
	}
}