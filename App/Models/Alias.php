<?php
namespace Storemaker\App\Models;
use Storemaker\System\Libraries;

class Alias extends Model {
	private $aliasesTable,
			$sablonsModel,
			$productSupplierModel,
			$typeToTable,
			$allAliases = [];

	function __construct() {
		parent::__construct();
		$this->aliasesTable = config::get('database/prefix').'aliases';
		$this->sablonsModel = new sabloaneModel();
		$this->productSupplierModel = new productSupplierModel();

		$this->typeToTable = [
			'productLink' => ['table' => $this->productSupplierModel->getTable(), 'column' => 'name', 'columnID' => 'attribute_id'],
			'category' => ['table' => $this->sablonsModel->getCategoriesModel()->getCategoryDescriptionTable(), 'column' => 'name', 'columnID' => 'category_id'],
			'attributeName' => ['table' => $this->sablonsModel->getAttributesDescriptionTable(), 'column' => 'name', 'columnID' => 'attribute_id'],
			'attributeValue' => ['table' => $this->sablonsModel->getProductsModel()->getProductsAttributesTable(), 'column' => 'test', 'columnID' => 'attribute_id'],
			'id' => ['table' => $this->sablonsModel->getProductsModel()->getProductsTable(), 'column' => 'product_id', 'columnID' => 'product_id'],
			'name' => ['table' => $this->sablonsModel->getProductsModel()->getProductsTable(), 'column' => 'name', 'columnID' => 'product_id'],
			'model' => ['table' => $this->sablonsModel->getProductsModel()->getProductsTable(), 'column' => 'model', 'columnID' => 'product_id'],
			'upc' => ['table' => $this->sablonsModel->getProductsModel()->getProductsTable(), 'column' => 'upc', 'columnID' => 'product_id'],
			'manufacturer' => ['table' => oc::getManufacturersTable(), 'column' => 'name', 'columnID' => 'manufacturer_id'],
		];

		$this->getAll();
	}

	public function getTypeToTable() {
		return $this->typeToTable;
	}

	public function get() {
		$stx = $this->db->select($this->aliasesTable, '*');

		if ($stx->getNumRows() > 0) {
			$results = ($stx->getNumRows() == 1) ? [$stx->results()] : $stx->results();

			foreach ($results as $key => $row) {
				if ($row->itemID != 0) {
					$results[$key]->replaceWith = $this->getItemValueByID($row->type, $row->itemID);
				}
			}
			return $results;
		} else {
			return false;
		}
	}

	public function getAll() {
		$stx = $this->db->select($this->aliasesTable, '*');

		if ($stx->getNumRows() > 0) {
			$results = ($stx->getNumRows() == 1) ? [$stx->results()] : $stx->results();

			foreach ($results as $row) {
				$row->search = ($row->array == '1') ? json_decode($row->search, true) : $row->search;
				$this->allAliases[$row->type] = (empty($this->allAliases[$row->type])) ? [] : $this->allAliases[$row->type];
				$this->allAliases[$row->type][] = $row;
			}
		}
	}

	public function getItemValueByID($type, $id) {
		$itemValue = $this->db->select($this->typeToTable[$type]['table'], $this->typeToTable[$type]['column'], [$this->typeToTable[$type]['columnID'], $id]);
		return ($itemValue->getNumRows() == 1) ? $itemValue->results()->{$this->typeToTable[$type]['column']} : false;
	}

	public function searchValue($type, $supplierID, $manufacturerID, $value, $getArray = false) {
		if (!empty($this->allAliases[$type])) {
			foreach ($this->allAliases[$type] as $typeArr) {
				if ($typeArr->supplierID == $supplierID && $typeArr->manufacturerID == $manufacturerID) {
					$value = $this->setValue($value, $typeArr, $getArray);
					break;
				} else if ($typeArr->supplierID == $supplierID && $typeArr->manufacturerID == '0') {
					$value = $this->setValue($value, $typeArr, $getArray);
				} else if ($typeArr->supplierID == '0' && $typeArr->manufacturerID == $manufacturerID) {
					$value = $this->setValue($value, $typeArr, $getArray);
				} else if ($typeArr->supplierID == '0' && $typeArr->manufacturerID == '0') {
					$value = $this->setValue($value, $typeArr, $getArray);
				}
			}
		}
		return $value;
	}

	private function setValue($value, $typeArr, $getArray) {
		if (($typeArr->array == '1' && in_array($value, $typeArr->search)) ||
			($typeArr->search == $value) ||
			($typeArr->prefix == '1' && substr($value, 0, strlen($typeArr->search)))) {
			$value = ($typeArr->prefix == '1' && substr($value, 0, strlen($typeArr->search)) == $typeArr->search) ?
						substr($value, strlen($typeArr->search)) :
					(($typeArr->itemID != '0') ?
						$this->getItemValueByID($typeArr->type, $typeArr->itemID) :
						$typeArr->replaceWith);
			return ($getArray) ? ['id' => $typeArr->itemID, 'value' => $value] : $value;
		}
		return $value;
	}

	public function save($id, $type, $supplierID, $manufacturerID, $itemID, $search, $array, $prefix, $replaceWith, $active) {
		$dataArr = [
			'type' => $type,
			'supplierID' => $supplierID,
			'manufacturerID' => $manufacturerID,
			'itemID' => $itemID,
			'search' => $search,
			'array' => $array,
			'prefix' => $prefix,
			'replaceWith' => $replaceWith,
			'active' => $active,
		];

		if ($id == '0') {
			$query = $this->db->insert($this->aliasesTable, $dataArr);
			return ($query) ? $query->getLastInsertID() : false;
		} else {
			return ($this->db->update($this->aliasesTable, $dataArr, $id)) ? $id : false;
		}
	}

	public function delete($id)	{
		return $this->db->delete($this->aliasesTable, $id);
	}
}