<?php

class sabloaneModel extends mainModel {
	private $sablonsTable,
			$sablonAttributesTable,
			$sablonGroupsTable,
			$sablonProductsTable,
			$attributesTable,
			$attributesDescriptionTable,
			$attributesGroupsTable,
			$attributesGroupsDescriptionTable,
			$productModel,
			$categoryModel;

	function __construct($productModel = false) {
		parent::__construct();

		$this->sablonsTable = config::get('database/prefix').'sablons';
		$this->sablonAttributesTable = config::get('database/prefix').'sablon_attributes';
		$this->sablonGroupsTable = config::get('database/prefix').'sablon_groups';
		$this->sablonProductsTable = config::get('database/prefix').'sablon_products';
		$this->attributesTable = oc::getDbPrexif().'attribute';
		$this->attributesDescriptionTable = oc::getDbPrexif().'attribute_description';
		$this->attributesGroupsTable = oc::getDbPrexif().'attribute_group';
		$this->attributesGroupsDescriptionTable = oc::getDbPrexif().'attribute_group_description';
		$this->productModel = ($productModel) ?: new produseModel($this);
		$this->categoryModel = categoriesModel::getInstance();
	}

	public function getProductsModel() {
		return $this->productModel;
	}

	public function getCategoriesModel() {
		return $this->categoryModel;
	}

	public function getSablonsTable() {
		return $this->sablonsTable;
	}

	public function getSablonAttributesTable() {
		return $this->sablonAttributesTable;
	}

	public function getSablonGroupsTable() {
		return $this->sablonGroupsTable;
	}

	public function getSablonProductsTable() {
		return $this->sablonProductsTable;
	}

	public function getAttributesTable() {
		return $this->attributesTable;
	}

	public function getAttributesDescriptionTable() {
		return $this->attributesDescriptionTable;
	}

	public function getAttributesGroupsTable() {
		return $this->attributesGroupsTable;
	}

	public function getAttributesGroupsDescriptionTable() {
		return $this->attributesGroupsDescriptionTable;
	}

	public function getSablonsCount($catID)	{
		return $this->db->count($this->sablonsTable, ['catID', $catID]);
	}

	public function getSablonsByCategory($catID) {
		$stx = $this->db->select($this->sablonsTable, '*', ['catID', $catID]);
		return ($stx->getNumRows() > 0) ? ((is_array($stx->results())) ? $stx->results() : [$stx->results()]) : (false);
	}

	public function getSablonName($ID)	{
		return $this->db->select($this->sablonsTable, 'name', ['id', $ID], '', 1)->results()->name;
	}

	public function getAttributesByGroup($sablonID, $groupID) {
		$stx = $this->db->query("SELECT sa.attributeID, sa.um, sa.descriere, sa.info, sa.hideLabel, sa.hide, oc_a.sort_order, oc_ad.name
			FROM ".$this->sablonAttributesTable." sa
			LEFT JOIN ".$this->attributesTable." oc_a
			ON oc_a.attribute_id = sa.attributeID
			LEFT JOIN ".$this->attributesDescriptionTable." oc_ad
			ON oc_ad.attribute_id = oc_a.attribute_id
			WHERE sa.sablonID = :sablonID AND sa.groupID = :groupID
			ORDER BY oc_a.sort_order", ['sablonID' => $sablonID, 'groupID' => $groupID]);

		return ($stx->getNumRows() > 0) ? ((is_array($stx->results())) ? $stx->results() : [$stx->results()]) : (false);
	}

	public function getSablonGroups($sablonID)	{
		$stx = $this->db->query("SELECT sg.groupID, oc_ag.sort_order, oc_agd.name
			FROM ".$this->sablonGroupsTable." sg
			LEFT JOIN ".$this->attributesGroupsTable." oc_ag
			ON oc_ag.attribute_group_id = sg.groupID
			LEFT JOIN ".$this->attributesGroupsDescriptionTable." oc_agd
			ON oc_agd.attribute_group_id = sg.groupID
			WHERE sg.sablonID = :sablonID
			ORDER BY oc_ag.sort_order ASC", ['sablonID' => $sablonID]);
		if ($stx->getNumRows() > 0) {
			$results = (is_array($stx->results())) ? $stx->results() : [$stx->results()];
			$return = [];

			foreach ($results as $groupRow) {
				$return[] = ['rows' => $groupRow, 'attributes' => $this->getAttributesByGroup($sablonID, $groupRow->groupID)];
			}
			return $return;
		}
		return false;
	}

	public function getSablonsTree() {
		$sablonsTree = [];

		$stx = $this->db->select($this->sablonsTable, '*');

		if ($stx->getNumRows() > 0) {
			$results = ($stx->getNumRows() == 1) ? [$stx->results()] : $stx->results();

			foreach ($results as $sablonRow) {
				$sablonsTree[$sablonRow->catID][] = $sablonRow;
			}
		}
		return $sablonsTree;
	}

	public function getProductsIDsBySablon($sablonID) {
		$stx = $this->db->select($this->sablonProductsTable, 'productID', ['sablonID', $sablonID]);
		return ($stx->getNumRows() > 0) ? (($stx->getNumRows() > 1) ? $stx->results() : [$stx->results()]) : false;
	}

	public function getProductSablonID($productID) {
		$stx = $this->db->select($this->sablonProductsTable, 'sablonID', ['productID', $productID]);
		return ($stx->getNumRows() > 0) ? $stx->results()->sablonID : 0;
	}

	public function addSablon($name, $catID) {
		return ($this->db->insert($this->sablonsTable, ['catID' => $catID, 'name' => $name])) ? $this->db->getLastInsertID() : false;
	}

	public function updateSablon($name, $ID) {
		return ($this->db->update($this->sablonsTable, ['name' => $name], $ID)) ? $ID : false;
	}

	public function addGroup($name, $sort, $sablonID) {
		if ($id = $this->checkGroupNameExists($name)) {
			if ($this->checkGroupExistsInSablon($id, $sablonID)) {
				return true;
			}
		} else {
			$id = $this->insertGroupInToOCTables($name, $sort);
		}

		return ($this->db->insert($this->sablonGroupsTable, ['groupID' => $id, 'sablonID' => $sablonID])) ? $id : false;
	}

	public function insertGroupInToOCTables($name, $sort)	{
		$this->db->insert($this->attributesGroupsTable, ['sort_order' => $sort]);
		$id = $this->db->getLastInsertID();
		$this->db->insert($this->attributesGroupsDescriptionTable, ['attribute_group_id' => $id, 'language_id' => oc::getLanguageID(), 'name' => $name]);
		return $id;
	}

	public function addAttributes($name, $um, $desc, $info, $hideLabel, $hide, $sort, $sablonID, $groupID) {
		if ($id = $this->checkAttributeNameExists($name)) {
			if ($this->checkAttribteBelongsToThisGroup($id, $groupID) === false) {
				return 'belongsToOtherGroup';
			}
		} else {
			$id = $this->insertAttributeInOCTables($name, $groupID, $sort);
		}
		return ($this->insertAttributeInSablonTable($id, $um, $desc, $info, $hideLabel, $hide, $sort, $sablonID, $groupID)) ? $id : false;
	}

	public function addProduct($id, $sablonID) {
		return $this->db->insert($this->sablonProductsTable, ['productID' => $id, 'sablonID' => $sablonID]);
	}

	public function insertAttributeInOCTables($name, $groupID, $sort) {
		$this->db->insert($this->attributesTable, ['attribute_group_id' => $groupID, 'sort_order' => $sort]);
		$id = $this->db->getLastInsertID();
		$this->db->insert($this->attributesDescriptionTable, ['attribute_id' => $id, 'language_id' => oc::getLanguageID(), 'name' => $name]);
		return $id;
	}

	private function insertAttributeInSablonTable($id, $um, $desc, $info, $hideLabel, $hide, $sort, $sablonID, $groupID) {
		return ($this->db->insert($this->sablonAttributesTable, ['attributeID' => $id, 'sablonID' => $sablonID, 'groupID' => $groupID, 'um' => $um, 'descriere' => $desc, 'info' => $info, 'hideLabel' => $hideLabel, 'hide' => $hide])) ? $id : false;
	}

	public function updateGroup($name, $ID, $sablonID, $editAll) {
		if ($editAll == 'true') {
			if ($newID = $this->checkGroupNameExists($name)) {
				return $this->updateSablonGroupID($ID, $newID, $sablonID);
			} else {
				return $this->updateGroupOCTable($name, $ID);
			}
		} else {
			$sort = $this->db->select($this->attributesGroupsTable, 'sort_order', ['attribute_group_id', $ID], '', 1)->results()->sort_order;
			$newID = ($this->checkGroupNameExists($name)) ?: $this->insertGroupInToOCTables($name, $sort);
			$this->db->update($this->attributesTable, ['attribute_group_id' => $newID], ['attribute_group_id', $ID]);
			return $this->updateSablonGroupID($ID, $newID, $sablonID);
		}
	}

	public function updateGroupOCTable($name, $ID) {
		return ($this->db->update($this->attributesGroupsDescriptionTable, ['name' => $name], ['attribute_group_id', $ID])) ? true : false;
	}

	public function updateAttributeGroup($ID, $groupID) {
		return ($this->db->update($this->attributesTable, ['attribute_group_id' => $groupID], ['attribute_id', $ID])) ? true : false;
	}

	private function updateSablonGroupID($oldID, $newID, $sablonID) {
		$this->db->update($this->sablonAttributesTable, ['groupID' => $newID], ['groupID', '=', $oldID, 'AND', 'sablonID', '=', $sablonID]);
		return ($this->db->update($this->sablonGroupsTable, ['groupID' => $newID], ['groupID', '=', $oldID, 'AND', 'sablonID', '=', $sablonID])) ? $newID : false;
	}

	public function updareAttributes($name, $um, $desc, $info, $hideLabel, $hide, $sablonID, $groupID, $id, $editAll, $changeName) {
		if ($editAll == 'true') {
			$this->updateAttributeInOCTables($name, $id);
			$newID = $id;
		} else if ($changeName == 'true') {
			if ($newID = $this->checkAttributeNameExists($name)) {
				if ($this->checkAttribteBelongsToThisGroup($id, $groupID) === false) {
					return 'belongsToOtherGroup';
				}
			} else {
				$newID = $this->insertAttributeInOCTables($name, $groupID, $sort);
			}
			$this->updateProductsAttribut($id, $newID, $sablonID);
		} else {
			$newID = $id;
		}
		return ($this->db->update($this->sablonAttributesTable, ['attributeID' => $newID, 'um' => $um, 'descriere' => $desc, 'info' => $info, 'hideLabel' => $hideLabel, 'hide' => $hide], ['attributeID', '=', $id, 'AND', 'groupID', '=', $groupID, 'AND', 'sablonID', '=', $sablonID], 1)) ? $newID : false;
	}

	private function updateProductsAttribut($attributeID, $newAttributeID, $sablonID) {
		$products = $this->getProductsIDsBySablon($sablonID);

		if ($products) {
			foreach ($products as $prdRow) {
				if ($this->productModel->checkHasAttribute($prdRow->productID, $attributeID)) {
					$this->db->update($this->productModel->getProductsAttributesTable(), ['attribute_id' => $newAttributeID], ['product_id', '=', $productID, 'AND', 'attribute_id', '=', $attributeID], 1);
				}
			}
		}
	}

	private function updateAttributeInOCTables($name, $id) {
		return $this->db->update($this->attributesDescriptionTable, ['name' => $name], ['attribute_id', $id]);
	}

	public function updateProduct($productID, $sablonID) {
		return $this->db->update($this->sablonProductsTable, ['sablonID' => $sablonID], ['productID', $productID], 1);
	}

	public function sort($what, $sort, $switchSort, $ID, $switchID, $sablonID, $count) {
		$table = $this->{$what.'Table'};
		$sablonsTable = ($what == 'attributes') ? $this->sablonAttributesTable : $this->sablonGroupsTable;
		$idColl = ($table == $this->attributesTable) ? 'attribute_id' : 'attribute_group_id';
		$operator = ($sort == $count && $switchSort == 1) ? '-' : '+';

		if ($this->db->update($table, ['sort_order' => $sort], [$idColl, $ID])) {
			if (($sort == $count && $switchSort == 1) || ($sort == 1 && $switchSort == $count)) {
				return $this->sortOthersAfterGoingOverTheEnd($table, $sablonsTable, $operator, $ID, $sablonID, $idColl);
			} else {
				return $this->db->update($table, ['sort_order' => $switchSort], [$idColl,  $switchID]);
			}
		} else return false;
	}

	private function sortOthersAfterGoingOverTheEnd($table, $sablonsTable, $operator, $ID, $sablonID, $idColl) {
		$return = true;
 		$sablon = $this->db->select($sablonsTable, '*', ['sablonID', $sablonID]);
		$sablonIDColl = ($idColl == 'attribute_id') ? 'attributeID' : 'groupID';

		if ($sablon->getNumRows() > 0) {
			$sablon = (is_array($sablon->results())) ? $sablon->results() : [$sablon->results()];

			foreach ($sablon as $sablonRow) {
				if ($ID != $sablonRow->{$sablonIDColl}) {
					if (!$this->db->query("UPDATE ".$table." SET sort_order = sort_order ".$operator." 1 WHERE ".$idColl." = :id LIMIT 1", ['id' => $sablonRow->{$sablonIDColl}])) {
						$return = false;
					}
				}
			}
		}
		return $return;
	}

	public function deleteAttributeFromOCTables($id) {
		$delete1 = $this->db->delete($this->attributesTable, ['attribute_id', $id], 1);
		$delete2 = $this->db->delete($this->attributesDescriptionTable, ['attribute_id', $id], 1);
		return ($delete2 && $delete2);
	}

	public function deleteAttributeFromSablon($id, $sablonID) {
		return $this->db->delete($this->sablonAttributesTable, ['attributeID', '=', $id, 'AND', 'sablonID', '=', $sablonID], 1);
	}

	public function deleteAttributeFromProductsBySablon($id, $sablonID)	{
		$products = $this->getProductsIDsBySablon($sablonID);
		$return = true;

		if ($products) {
			foreach ($products as $prdRow) {
				if ($this->productModel->checkHasAttribute($prdRow->productID, $id)) {
					if (!$this->productModel->deleteAttribut($prdRow->productID, $id)) {
						$return = false;
					}
				}
			}
		}
		return $return;
	}

	public function deleteGroup($sablonID, $id) {
		return $this->db->delete($this->sablonGroupsTable, ['groupID', '=', $id, 'AND', 'sablonID', '=', $sablonID], 1);
	}

	public function deleteSablon($id) {
		return $this->db->delete($this->sablonsTable, $id, 1);
	}

	public function deleteAttributesByGroup($groupID, $sablonID) {
		return $this->db->delete($this->sablonAttributesTable, ['groupID', '=' , $groupID, 'AND', 'sablonID', '=', $sablonID]);
	}

	public function deleteAttributesBySablon($sablonID) {
		return $this->db->delete($this->sablonAttributesTable, ['sablonID', $sablonID]);
	}

	public function deleteGroupsBySablon($sablonID) {
		return $this->db->delete($this->sablonGroupsTable, ['sablonID', $sablonID]);
	}

	public function deleteProductsBySablon($sablonID) {
		$products = $this->getProductsIDsBySablon($sablonID);
		$return = true;

		if ($products) {
			foreach ($products as $prdRow) {
				if (!$this->productModel->delete($prdRow->productID)) {
					$return = false;
				}
			}
		}
		return $return;
	}

	public function deleteByCat($catID)	{
		if ($sablonRow = $this->getSablonsByCategory($catID)) {
			return $this->deleteSablon($sablonRow->id);
		}
		return true;
	}

	public function deleteProduct($id) {
		return $this->db->delete($this->sablonProductsTable, ['productID', $id]);
	}

	private function updateSortForDelete($table, $sort, $pivot, $id) {
		return $this->db->query("UPDATE ".$table." SET sort = sort - 1 WHERE ".$pivot." = :id AND sort > :sort", ['sort' => $sort, 'id' => $id]);
	}

	public function moveAllFromCat($fromID, $toID) {
		return $this->db->update($this->sablonsTable, ['catID' => $toID], ['catID', $fromID]);
	}

	public function checkGroupNameExists($name) {
		$stx = $this->db->select($this->attributesGroupsDescriptionTable, 'attribute_group_id', ['name', $name], '', 1);
		return ($stx->getNumRows() > 0) ? $stx->results()->attribute_group_id : false;
	}

	public function checkGroupExistsInSablon($groupID, $sablonID) {
		return ($this->db->count($this->sablonGroupsTable, ['groupID', '=', $groupID, 'AND', 'sablonID', '=', $sablonID]) > 0) ? true : false;
	}

	public function checkAttributeNameExists($name) {
		$stx = $this->db->select($this->attributesDescriptionTable, 'attribute_id', ['name', $name], '', 1);
		return ($stx->getNumRows() > 0) ? $stx->results()->attribute_id : false;
	}

	public function checkAttribteBelongsToThisGroup($attributeID, $groupID) {
		return ($this->db->count($this->attributesTable, ['attribute_id', '=', $attributeID, 'AND', 'attribute_group_id', '=', $groupID]) > 0) ? true : false;
	}
}