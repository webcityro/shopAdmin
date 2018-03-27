<?php

class Product extends mainModel {
	private $productsTable,
			$productsAttributesTable,
			$productsDescriptionTable,
			$productsDiscountTable,
			$productsFilterTable,
			$productsImageTable,
			$productsOptionValueTable,
			$productsRecurringTable,
			$productsRelatedTable,
			$productsRewardTable,
			$productsSpecialTable,
			$productsCategoryTable,
			$productsDownloadTable,
			$productsLayoutTable,
			$productsStoreTable,
			$sablonModel,
			$categoryModel;

	function __construct($sablonModel = false) {
		parent::__construct();

		$this->productsTable = oc::getDbPrexif().'product';
		$this->productsAttributesTable = oc::getDbPrexif().'product_attribute';
		$this->productsDescriptionTable = oc::getDbPrexif().'product_description';
		$this->productsDiscountTable = oc::getDbPrexif().'product_discount';
		$this->productsFilterTable = oc::getDbPrexif().'product_filter';
		$this->productsImageTable = oc::getDbPrexif().'product_image';
		$this->productsOptionValueTable = oc::getDbPrexif().'product_option_value';
		$this->productsRecurringTable = oc::getDbPrexif().'product_recurring';
		$this->productsRelatedTable = oc::getDbPrexif().'product_related';
		$this->productsRewardTable = oc::getDbPrexif().'product_reward';
		$this->productsSpecialTable = oc::getDbPrexif().'product_special';
		$this->productsCategoryTable = oc::getDbPrexif().'product_to_category';
		$this->productsDownloadTable = oc::getDbPrexif().'product_to_download';
		$this->productsLayoutTable = oc::getDbPrexif().'product_to_layout';
		$this->productsStoreTable = oc::getDbPrexif().'product_to_store';
		$this->sablonModel = ($sablonModel) ?: new sabloaneModel($this);
		$this->categoryModel = categoriesModel::getInstance();
	}

	public function getProductsTable() {
		return $this->productsTable;
	}

	public function getProductsAttributesTable() {
		return $this->productsAttributesTable;
	}

	public function getProductsDescriptionTable() {
		return $this->productsDescriptionTable;
	}

	public function getProductsDiscountTable() {
		return $this->productsDiscountTable;
	}

	public function getProductsFilterTable() {
		return $this->productsFilterTable;
	}

	public function getProductsImageTable() {
		return $this->productsImageTable;
	}

	public function getProductsOptionValueTable() {
		return $this->productsOptionValueTable;
	}

	public function getProductsRecurringTable() {
		return $this->productsRecurringTable;
	}

	public function getProductsRelatedTable() {
		return $this->productsRelatedTable;
	}

	public function getProductsRewardTable() {
		return $this->productsRewardTable;
	}

	public function getProductsSpecialTable() {
		return $this->productsSpecialTable;
	}

	public function getProductsCategoryTable() {
		return $this->productsCategoryTable;
	}

	public function getProductsDownloadTable() {
		return $this->productsDownloadTable;
	}

	public function getProductsLayoutTable() {
		return $this->productsLayoutTable;
	}

	public function getProductsStoreTable() {
		return $this->productsStoreTable;
	}

	public function getSablonModel() {
		return $this->sablonModel;
	}

	public function getProductsTree() {
		$productsTree = [];

		$stx = $this->db->query("SELECT oc_p.product_id AS id, oc_p.image, oc_pcat.category_id AS catID, oc_pdesc.name
								FROM ".$this->productsTable." oc_p
								LEFT JOIN ".$this->productsDescriptionTable." oc_pdesc
								ON oc_pdesc.product_id = oc_p.product_id
								LEFT JOIN ".$this->productsCategoryTable." oc_pcat
								ON oc_pcat.product_id = oc_p.product_id
								ORDER BY oc_p.sort_order ASC");

		if ($stx->getNumRows() > 0) {
			$results = ($stx->getNumRows() == 1) ? [$stx->results()] : $stx->results();

			foreach ($results as $productRow) {
				$productsTree[$productRow->catID][] = $productRow;
			}
		}
		return $productsTree;
	}

	public function getCategoryPresetes($catID)	{
		$stx = $this->db->select($this->categoryModel->getCategoryPresetsTable(), '*', ['catID', $catID], '', 1);
		return ($stx && $stx->getNumRows() == 1) ? $stx->results() : false;
	}

	public function getData($id) {
		$stx = $this->db->query("SELECT oc_p.*, oc_pdesc.*
								 FROM ".$this->productsTable." oc_p
								 LEFT JOIN ".$this->productsDescriptionTable." oc_pdesc
								 ON oc_pdesc.product_id = oc_p.product_id
								 WHERE oc_p.product_id = :productID
								 LIMIT 1", ['productID' => $id]);
		return $stx->results();
	}

	public function getGroupByName($name) {
		$stx = $this->db->query("SELECT oc_ag.attribute_group_id AS id, oc_ag.sort_order AS sort
								 FROM ".$this->sablonModel->getAttributesGroupsTable()." oc_ag
								 RIGHT JOIN ".$this->sablonModel->getAttributesGroupsDescriptionTable()." oc_agd
								 ON oc_ag.attribute_group_id = oc_agd.attribute_group_id
								 WHERE oc_agd.name = :name
								 LIMIT 1", ['name' => $name]);
		return ($stx->getNumRows() == 1) ? $stx->results() : false;
	}

	public function getAttributeByName($name) {
		$stx = $this->db->query("SELECT oc_a.attribute_id AS id, oc_a.attribute_group_id AS groupID, oc_a.sort_order AS sort
								 FROM ".$this->sablonModel->getAttributesTable()." oc_a
								 RIGHT JOIN ".$this->sablonModel->getAttributesDescriptionTable()." oc_ad
								 ON oc_a.attribute_id = oc_ad.attribute_id
								 WHERE oc_ad.name = :name
								 LIMIT 1", ['name' => $name]);
		return ($stx->getNumRows() == 1) ? $stx->results() : false;
	}

	public function getAttributesByGroup($productID, $groupID) {
		$attributes = $this->db->query("SELECT oc_pa.attribute_id, oc_a.attribute_group_id AS groupID
									FROM ".$this->sablonModel->getAttributesTable()." oc_a
									RIGHT JOIN ".$this->productsAttributesTable." oc_pa
									ON oc_pa.attribute_id = oc_a.attribute_id
									WHERE oc_pa.product_id = :productID AND oc_a.attribute_group_id = :groupID", ['productID' => $productID, 'groupID' => $groupID]);
		return ($attributes && $attributes->getNumRows() > 0) ? ((is_array($attributes->results())) ? $attributes->results() : [$attributes->results()]) : false;
	}

	public function getAttributes($id, $sablonID) {
		$groupsArray = [];

		$stx = $this->db->query("SELECT oc_pa.*, oc_a.attribute_group_id AS groupID, oc_ad.name, oc_ag.sort_order, oc_agd.name AS groupName
								 FROM ".$this->productsAttributesTable." oc_pa
								 LEFT JOIN ".$this->sablonModel->getAttributesTable()." oc_a
								 ON oc_a.attribute_id = oc_pa.attribute_id
								 LEFT JOIN ".$this->sablonModel->getAttributesGroupsTable()." oc_ag
								 ON oc_ag.attribute_group_id = oc_a.attribute_group_id
								 LEFT JOIN ".$this->sablonModel->getAttributesGroupsDescriptionTable()." oc_agd
								 ON oc_agd.attribute_group_id = oc_a.attribute_group_id
								 LEFT JOIN ".$this->sablonModel->getAttributesDescriptionTable()." oc_ad
								 ON oc_ad.attribute_id = oc_pa.attribute_id
								 WHERE oc_pa.product_id = :productID
								 ORDER BY oc_ag.sort_order, oc_a.sort_order", ['productID' => $id]);
		$prdAttributes = ($stx->getNumRows() > 0) ? (($stx->getNumRows() == 1) ? [$stx->results()] : $stx->results()) : (false);
		if ($prdAttributes) {
			foreach ($prdAttributes as $attribute) {
				if (!isset($groupsArray[$attribute->groupID])) {
					$groupsArray[$attribute->groupID] = ['name' => $attribute->groupName,
														 'sablon' => 'false',
														 'attributes' => [],
														 'style' => 'prdAttrOriginal'];
				}

				$groupsArray[$attribute->groupID]['attributes'][$attribute->attribute_id] = [
					'name' => $attribute->name,
					'value' => $attribute->text,
					'um' => '',
					'sort' => $attribute->sort_order,
					'desc' => '',
					'extraInfo' => '',
					'sablon' => 'false',
					'style' => 'prdAttrOriginal'];
			}
		}

		if ($sablonID != 0) {
			$sablonGroups = $this->sablonModel->getSablonGroups($sablonID);

			foreach ($sablonGroups as $group) {
				$groupRow = $group['rows'];

				if (empty($groupsArray[$groupRow->groupID])) {
					$groupsArray[$groupRow->groupID] = ['name' => $groupRow->name,
														'sablon' => 'true',
														'attributes' => [],
													 	'style' => 'prdAttrSablonNotUsed'];
				} else {
					$groupsArray[$groupRow->groupID]['sablon'] = 'true';
					$groupsArray[$groupRow->groupID]['style'] = 'prdAttrSablon';
				}

				if ($group['attributes']) {
					foreach ($group['attributes'] as $attrRow) {
						$value = $groupsArray[$groupRow->groupID]['attributes'][$attrRow->attributeID]['value'];
						$style = (empty($groupsArray[$groupRow->groupID]['attributes'][$attrRow->attributeID])) ? 'prdAttrOriginal' : (($value == '') ? 'prdAttrSablonNotUsed' : 'prdAttrSablon');
						$groupsArray[$groupRow->groupID]['attributes'][$attrRow->attributeID] = [
							'name' => $attrRow->name,
							'value' => str_replace($attrRow->um, '', $value),
							'um' => $attrRow->um,
							'sort' => $attrRow->sort_order,
							'desc' => $attrRow->descriere,
							'extraInfo' => $attrRow->info,
							'sablon' => 'true',
							'style' => $style];
					}
				}
			}
		}
		return $groupsArray;
	}

	public function getImages($id) {
		$stx = $this->db->select($this->productsImageTable, '*', ['product_id', $id]);
		return ($stx && $stx->getNumRows() > 0) ? (($stx->getNumRows() == 1) ? [$stx->results()] : $stx->results()) : false;
	}

	public function getImage($productID, $imageID) {
		return $this->db->select($this->productsImageTable, '*', ['product_id', '=', $productID, 'AND', 'product_image_id', '=', $imageID], '', 1)->results();
	}

	public function addData($data) {
		// die(print_r($data));
		$insertData = $this->db->insert($this->productsTable, [
				'model'           => $data->prdModel,
				'sku'             => '',
				'upc'             => $data->prdCode,
				'ean'             => '',
				'jan'             => '',
				'isbn'            => '',
				'mpn'             => '',
				'location'        => '',
				'quantity'        => $data->prdStoc,
				'stock_status_id' => 7,
				'manufacturer_id' => $data->prdMakerID,
				'shipping'        => 1,
				'tax_class_id'	  => 9,
				'price'           => $data->prdPrice,
				'weight'          => $data->prdWeight,
				'weight_class_id' => $data->prdWeightClassID,
				'length'		  => $data->prdLenght,
				'length_class_id' => $data->prdLengthClassID,
				'width' 		  => $data->prdWidth,
				'height' 		  => $data->prdHeight,
				'minimum' 		  => 1,
				'status'  		  => (empty($data->prdStatus)) ? 0 : $data->prdStatus,
				'date_available'  => date('Y-m-d H:i:s'),
				'date_added' 	  => date('Y-m-d H:i:s'),
				'date_modified'	  => date('Y-m-d H:i:s')
			]);

		if ($insertData) {
			$id = $this->db->getLastInsertID();

			$this->db->insert($this->productsDescriptionTable, [
					'product_id' => $id,
					'language_id' => oc::getLanguageID(),
					'name' => $data->prdName,
					'description' => (empty($data->prdDesc)) ? '' : $data->prdDesc,
					'tag' => '',
					'meta_title' => $data->prdMetaTitle,
					'meta_description' => (empty($data->prdMetaDesc)) ? '' : $data->prdMetaDesc,
					'meta_keyword' => (empty($data->prdMetaKeywords)) ? '' : $data->prdDesc
				]);

			return $id;
		}
		return false;
	}

	public function addToCaregory($id, $catID) {
		return $this->db->insert($this->productsCategoryTable, ['product_id' => $id, 'category_id' => $catID]);
	}

	public function addToStore($id, $storeID) {
		return $this->db->insert($this->productsStoreTable, ['product_id' => $id, 'store_id' => $storeID]);
	}

	public function addAttribute($id, $attributeID, $value) {
		return $this->db->insert($this->productsAttributesTable, ['product_id' => $id,
																  'attribute_id' => $attributeID,
																  'language_id' => oc::getLanguageID(),
																  'text' => $value]);
	}

	public function addImage($id, $image) {
		return $this->db->insert($this->productsImageTable, ['product_id' => $id, 'image' => $image, 'sort_order' => '0']);
	}

	public function setProductImage($id, $image) {
		return $this->db->update($this->productsTable, ['image' => $image], ['product_id', '=', $id]);
	}

	public function updateData($id, $data) {
		$productTableArr = [
			'prdModel' 		   => 'model',
			'prdCode'  		   => 'upc',
			'prdStoc'  		   => 'quantity',
			'prdMakerID' 	   => 'manufacturer_id',
			'prdPrice'   	   => 'price',
			'prdWeight'  	   => 'weight',
			'prdWeightClassID' => 'weight_class_id',
			'prdLenght' 	   => 'length',
			'prdLengthClassID' => 'length_class_id',
			'prdHeight' 	   => 'height',
			'prdwidth'	 	   => 'width',
			'prdStatus' 	   => 'status'
		];
		$productDescriptionTableArr = [
			'prdName' 		  => 'name',
			'prdDesc' 		  => 'description',
			'prdMetaTitle'    => 'meta_title',
			'prdMetaDesc'     => 'meta_description',
			'prdMetaKeywords' => 'meta_keyword'
		];
		$prdUpdateArr = [];
		$prdDescriptionUpdateArr = [];

		foreach ($data as $key => $update) {
			if (!empty($productTableArr[$key])) {
				$prdUpdateArr[$productTableArr[$key]] = $update->update;
			} else if (!empty($productDescriptionTableArr[$key])) {
				$prdDescriptionUpdateArr[$productDescriptionTableArr[$key]] = $update->update;
			}
		}
		$update1 = true;
		$update2 = true;

		if (!empty($prdUpdateArr)) {
			$prdUpdateArr['date_modified'] = date('Y-m-d H:i:s');
			$update1 = $this->db->update($this->productsTable, $prdUpdateArr, ['product_id', $id]);
		}
		if (!empty($prdDescriptionUpdateArr)) {
			$update2 = $this->db->update($this->productsDescriptionTable, $prdDescriptionUpdateArr, ['product_id', $id]);
		}

		return ($update1 && $update2) ? true : false;
	}

	public function updateCaregory($id, $catID) {
		return $this->db->update($this->productsCategoryTable, ['category_id' => $catID], ['product_id', $id], 1);
	}

	public function changeAttributesGroup($productID, $oldGroupID, $newGroupID) {
		$return = true;
		$attributes = $this->getAttributesByGroup($productID, $oldGroupID);

		if ($attributes) {
			foreach ($attributes as $attrRow) {
				if (!$this->sablonModel->updateAttributeGroup($attrRow->attribute_id, $newGroupID)) {
					$return = false;
				}
			}
		}
		return $return;
	}

	public function updateAttribute($productID, $attributeID, $data) {
		return $this->db->update($this->productsAttributesTable, $data, ['product_id', '=', $productID, 'AND', 'attribute_id', '=', $attributeID], 1);
	}

	public function deleteAttribute($productID, $attributeID) {
		return $this->db->delete($this->productsAttributesTable, ['product_id', '=', $productID, 'AND', 'attribute_id', '=', $attributeID], 1);
	}

	public function deleteAttributes($id) {
		return $this->db->delete($this->productsAttributesTable, ['attribute_id', $id]);
	}

	public function deleteAttributesByProduct($id) {
		return $this->db->delete($this->productsAttributesTable, ['product_id', $id]);
	}

	public function deleteAttributesByGroup($productID, $groupID) {
		$attributes = $this->getAttributesByGroup($productID, $groupID);
		$return = true;
		if ($attributes) {
			foreach ($attributes as $attrRow) {
				if (!$this->deleteAttribute($productID, $attrRow->attribute_id)) {
					$return = false;
				}
			}
		}
		return $return;
	}

	public function deleteImage($productID, $imageID = 0) {
		$imgRow = (ctype_digit($productID)) ? $this->getImage($productID, $imageID) : $productID;
		file::remove(oc::getImagesDIR().$imgRow->image);
		file::remove(oc::getImagesDIR().'cache/'.$imgRow->image);
		return $this->db->delete($this->productsImageTable, ['product_image_id', '=', $imgRow->product_image_id, 'AND', 'product_id', '=', $imgRow->product_id], 1);
	}

	public function deleteImages($productID) {
		$images = $this->getImages($productID);
		$deleted = true;

		if ($images) {
			foreach ($images as $imgRow) {
				if (!$this->deleteImage($imgRow)) {
					$deleted = false;
				}
			}
		}
		return $deleted;
	}

	public function delete($id)	{
		$deleted = true;

		if (!$this->deleteImages($id)) {
			$deleted = false;
		}
		if (!$this->deleteAttributesByProduct($id)) {
			$deleted = false;
		}
		if (!$this->db->delete($this->productsTable, ['product_id', $id])) {
			$deleted = false;
		}
		if (!$this->db->delete($this->productsDescriptionTable, ['product_id', $id])) {
			$deleted = false;
		}
		if (!$this->db->delete($this->productsDiscountTable, ['product_id', $id])) {
			$deleted = false;
		}
		if (!$this->db->delete($this->productsFilterTable, ['product_id', $id])) {
			$deleted = false;
		}
		if (!$this->db->delete($this->productsOptionValueTable, ['product_id', $id])) {
			$deleted = false;
		}
		if (!$this->db->delete($this->productsRecurringTable, ['product_id', $id])) {
			$deleted = false;
		}
		if (!$this->db->delete($this->productsRelatedTable, ['product_id', $id])) {
			$deleted = false;
		}
		if (!$this->db->delete($this->productsRewardTable, ['product_id', $id])) {
			$deleted = false;
		}
		if (!$this->db->delete($this->productsSpecialTable, ['product_id', $id])) {
			$deleted = false;
		}
		if (!$this->db->delete($this->productsCategoryTable, ['product_id', $id])) {
			$deleted = false;
		}
		if (!$this->db->delete($this->productsDownloadTable, ['product_id', $id])) {
			$deleted = false;
		}
		if (!$this->db->delete($this->productsLayoutTable, ['product_id', $id])) {
			$deleted = false;
		}
		if (!$this->db->delete($this->productsStoreTable, ['product_id', $id])) {
			$deleted = false;
		}
		if (!$this->sablonModel->deleteProduct($id)) {
			$deleted = false;
		}
		return $deleted;
	}

	public function deleteByCat($catID)	{
		$deleted = true;
		$stx = $this->db->select($this->productsCategoryTable, 'product_id', ['category_id', $catID]);

		if ($stx->getNumRows() > 0) {
			$results = (is_array($stx->results())) ? $stx->results() : [$stx->results()];

			foreach ($results as $productRow) {
				if (!$this->delete($productRow->product_id)) {
					$deleted = false;
				}
			}
		}
		return $deleted;
	}

	public function moveAllFromCat($fromID, $toID) {
		return $this->db->update($this->productsCategoryTable, ['category_id' => $toID], ['category_id', $fromID]);
	}

	public function checkHasAttribute($productID, $attributeID) {
		return ($this->db->count($this->productsAttributesTable, ['product_id', '=', $productID, 'AND', 'attribute_id', '=', $attributeID]) > 0) ? true : false;
	}
}