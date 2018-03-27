<?php
namespace Storemaker\App\Models;
use Storemaker\System\Libraries\Model;
use Storemaker\System\Libraries\Config;

class Category extends Model {
	private $categoryTable,
			$categoryDescriptionTable,
			$categoryFilterTable,
			$categoryToStoreTable,
			$categoryToLayoutTable,
			$productToCategoryTable,
			$urlAliasTable,
			$categoryPresetsTable;

	private static $instance = false;

	function __construct() {
		parent::__construct();
		$this->categoryTable = oc::getDbPrexif().'category';
		$this->categoryDescriptionTable = oc::getDbPrexif().'category_description';
		$this->categoryFilterTable = oc::getDbPrexif().'category_filter';
		$this->categoryToStoreTable = oc::getDbPrexif().'category_to_store';
		$this->categoryToLayoutTable = oc::getDbPrexif().'category_to_layout';
		$this->productToCategoryTable = oc::getDbPrexif().'product_to_category';
		$this->urlAliasTable = oc::getDbPrexif().'url_alias';
		$this->categoryPresetsTable = config::get('database/prefix').'category_preset';
	}

	public static function getInstance() {
		if (!self::$instance) {
			self::$instance = new categoriesModel();
		}
		return self::$instance;
	}

	public function getCategoryTable() {
		return $this->categoryTable;
	}

	public function getCategoryDescriptionTable() {
		return $this->categoryDescriptionTable;
	}

	public function getCategoryPresetsTable() {
		return $this->categoryPresetsTable;
	}

	public function getProductToCategoryTable() {
		return $this->productToCategoryTable;
	}

	public function getByParent($parentID = 0) {
		$stx = $this->db->select($this->categoryTable, '*', ['parent_id', $parentID]);
		return ($stx->getNumRows() > 0) ? $stx->results() : false;
	}

	public function get($id) {
		$stx = $this->db->query("SELECT oc_category.*, oc_category_description.*
								FROM oc_category
								RIGHT JOIN oc_category_description
								ON oc_category.category_id = oc_category_description.category_id
								WHERE oc_category.category_id = :id
								LIMIT 1", ['id' => $id]);
		if ($stx->getNumRows() > 0) {
			$catRow = $stx->results();
			$catRow->presets = $this->db->select($this->categoryPresetsTable, '*', ['catID', $id])->results();
			return $catRow;
		}
		return false;
	}

	public function getImage($catID) {
		return $this->db->select($this->categoryTable, 'image', ['category_id', $catID])->results()->image;
	}

	public function add($image, $name, $catDesc, $catMetaTitle, $catMetaDesc, $catMetaKeywords, $catStatus, $catTop, $catTopColumn, $sort, $parentID) {
		$insert1 = $this->db->query("INSERT INTO ".$this->categoryTable." VALUES(`category_id`, :image, :parentID, :top, :column, :sort, :status, NOW(), NOW())", [
			'image' => $image,
			'parentID' => $parentID,
			'top' => $catTop,
			'column' => $catTopColumn,
			'sort' => $sort,
			'status' => $catStatus]);

		if ($insert1) {
			$id = $this->db->getLastInsertID();
			$insert2 = $this->db->insert($this->categoryDescriptionTable, [
				'category_id' => $id,
				'language_id' => oc::getLanguageID(),
				'name' => $name,
				'description' => $catDesc,
				'meta_title' => $catMetaTitle,
				'meta_description' => $catMetaDesc,
				'meta_keyword' => $catMetaKeywords]);

			if ($insert2) {
				$insert3 = $this->db->insert($this->categoryToStoreTable, ['category_id' => $id, 'store_id' => oc::getStoreID()]);
				return ($insert3) ? $id : false;
			}
			return false;
		} else {
			return false;
		}
	}

	public function insertPresets($lenght, $width, $height, $Weight, $lenghtClassID, $weightClassID, $catID) {
		return $this->db->insert($this->categoryPresetsTable, [
			'catID' => $catID,
			'lenght' => $lenght,
			'width' => $width,
			'height' => $height,
			'Weight' => $Weight,
			'lenghtClassID' => $lenghtClassID,
			'weightClassID' => $weightClassID
		]);
	}

	public function update($image, $name, $catDesc, $catMetaTitle, $catMetaDesc, $catMetaKeywords, $catStatus, $catTop, $catTopColumn, $id) {
		$update = $this->db->query("UPDATE $this->categoryTable SET image = :image, top = :top, `column` = :column, status = :status, date_modified = NOW() WHERE category_id = :catID LIMIT 1", [
			'image' => $image,
			'top' => $catTop,
			'column' => $catTopColumn,
			'status' => $catStatus,
			'catID' => $id]);
		if ($update) {
			if ($this->db->update($this->categoryDescriptionTable, ['name' => $name, 'description' => $catDesc, 'meta_title' => $catMetaTitle, 'meta_description' => $catMetaDesc, 'meta_keyword' => $catMetaKeywords], ['category_id', $id])) {
				return $id;
			}
			return false;
		} else {
			return false;
		}
	}

	public function updatePresets($lenght, $width, $height, $Weight, $lenghtClassID, $weightClassID, $catID) {
		return $this->db->update($this->categoryPresetsTable, [
			'lenght' => $lenght,
			'width' => $width,
			'height' => $height,
			'Weight' => $Weight,
			'lenghtClassID' => $lenghtClassID,
			'weightClassID' => $weightClassID
		], ['catID', $catID]);
	}

	public function sort($oldSort, $newSort, $id, $parentID) {
		if ($this->db->update($this->categoryTable, ['sort_order' => $newSort], ['category_id', $id])) {
			if ($oldSort < $newSort) { // down
				$query = $this->db->query("UPDATE ".$this->categoryTable." SET sort_order = sort_order - 1 WHERE category_id != :id AND parent_id = :parentID AND sort_order <= :newSort AND sort_order > :oldSort", [
					'id' => $id,
					'parentID' => $parentID,
					'newSort' => $newSort,
					'oldSort' => $oldSort]);
			} else { // up
				$query = $this->db->query("UPDATE ".$this->categoryTable." SET sort_order = sort_order + 1 WHERE category_id != :id AND parent_id = :parentID AND sort_order >= :newSort AND sort_order < :oldSort", [
					'id' => $id,
					'parentID' => $parentID,
					'newSort' => $newSort,
					'oldSort' => $oldSort]);
			}


			if ($query) {
				return true;
			}
		}
		return false;
	}

	public function delete($id, $what) {
		$thisCatParent = $this->db->select($this->categoryTable, 'parent_id', ['category_id', $id])->results()->parent_id;

		switch ($what) {
			case 'all':// sterge absolut  tot
				return ($this->doRecursiveDelete($id, ['deleteArticle' => []])) ? $thisCatParent : false;
			break;
			case 'allCats':// sterge absolut  tot in afara se articole
				return ($this->doRecursiveDelete($id, ['changeArticleParent' => [$thisCatParent],
													   'deleteCat' => []], true, false)) ? $thisCatParent : false;
			break;
			case 'firstCatLevel':// merge ok
				return ($this->doRecursiveDelete($id, ['changeArticleParent' => [$id],
													   'changeAllParents' => [$id],
													   'deleteCat' => [],
													   ], false, false)) ? $thisCatParent : false;

			break;
			case 'onleThis':// merge ok
				return ($this->doRecursiveDelete($id, ['changeArticleParent' => [$thisCatParent],
													   'changeParent' 		 => [$thisCatParent]], false)) ? $thisCatParent : false;
			break;
			case 'articles':
				return ($this->deleteArticle($id)) ? $thisCatParent : false;
			break;

		}
	}

	private function doRecursiveDelete($id, $callbecks, $recursive = true, $delete =  true) {
		$cats = $this->getByParent($id);

		if ($cats) {
			$cats = (!is_array($cats)) ? [$cats] : $cats;
			foreach ($cats as $catRow) {
				$this->callDeleteMethods($catRow->category_id, $callbecks);

				if ($recursive) {
					$this->doRecursiveDelete($catRow->category_id, $callbecks);
				}
			}
		}

		if ($delete) {
			$this->callDeleteMethods($id, $callbecks);
			return $this->deleteCat($id);
		}
		return true;
	}

	private function callDeleteMethods($id, $callbecks)	{
		foreach ($callbecks as $callbeck => $args) {
			foreach ($args as $key => $arg) {
				$args[$key] = (isset($catRow->$arg)) ? $catRow->$arg : $arg;
			}
			array_unshift($args, $id);
			call_user_func_array([$this, $callbeck], $args);
		}
	}

	private function deleteCat($id) {
		$sort = $this->changeSortForDeleteCat($id);

		$delete1 = $this->db->delete($this->categoryTable, ['category_id', $id], 1);
		$delete2 = $this->db->delete($this->categoryDescriptionTable, ['category_id', $id], 1);
		$delete3 = $this->db->delete($this->categoryFilterTable, ['category_id', $id], 1);
		$delete4 = $this->db->delete($this->categoryToStoreTable, ['category_id', $id], 1);
		$delete5 = $this->db->delete($this->categoryToLayoutTable, ['category_id', $id], 1);
		$delete6 = $this->db->delete($this->productToCategoryTable, ['category_id', $id], 1);
		$delete7 = $this->db->delete($this->urlAliasTable, ['query', 'category_id='.$id], 1);
		$delete8 = $this->deletePresets($id);

		if ($delete1 && $delete2 && $delete3 && $delete4 && $delete5 && $delete6 && $delete7 && $delete8 && $sort) {
			return true;
		}
		return false;
	}

	public function deletePresets($catID) {
		return $this->db->delete($this->categoryPresetsTable, ['catID', $catID], 1);
	}

	public function deleteImage($catID)	{
		$image = $this->getImage($catID);

		if (!empty($image)) {
			return file::remove(oc::getImagesDIR().$image) && file::remove(oc::getImagesDIR().'cache/'.$image);
		}
		return true;
	}

	private function changeSortForDeleteCat($id)	{
		$catRow = $this->db->select($this->categoryTable, 'parent_id, sort_order', ['category_id', $id])->results();
		return $this->db->query("UPDATE ".$this->categoryTable." SET sort_order = sort_order - 1 WHERE parent_id = :parentID AND sort_order > :sort", [
			'parentID' => $catRow->parent_id,
			'sort' => $catRow->sort_order]);
	}

	public function changeParent($id, $parentID) {
		$sort = $this->db->select($this->categoryTable, 'sort_order', ['category_id', '!=', $parentID, 'AND', 'parent_id', '=', $parentID], 'sort_order DESC', 1);
		$sort = ($sort->getNumRows() == 1) ? $sort->results()->sort_order+1 : 0;

		$this->changeSortForDeleteCat($id);

		if ($this->db->update($this->categoryTable, ['parent_id' => $parentID, 'sort_order' => $sort], ['category_id', $id])) {
			return $this->changeSortForDeleteCat($id);
		}
		return false;
	}

	public function changeAllParents($fromParentID, $toParentID) {
		$this->changeAllSortsForMoveingToNewParent($fromParentID, $toParentID);
		return $this->db->update($this->categoryTable, ['parent_id' => $toParentID], ['parent_id', $fromParentID]);
	}

	private function changeAllSortsForMoveingToNewParent($fromParentID, $toParentID) {
		$catsToChange = $this->getByParent($fromParentID);
		$newSort = $this->db->select($this->categoryTable, 'sort_order', ['parent_id', $toParentID], 'sort_order DESC', 1)->results()->sort_order;

		foreach ($catsToChange as $catRow) {
			$newSort++;
			$this->db->update($this->categoryTable, ['sort_order' => $newSort], ['category_id', $catRow->category_id], 1);
		}
	}

	public function changeArticleParent($id, $parentID) {
		$sablons = new sabloaneModel();

		return $sablons->moveAllFromCat($id, $parentID) && $sablons->getProductsModel()->moveAllFromCat($id, $parentID);
	}

	public function deleteArticle($parentID) {
		$sablons = new sabloaneModel();

		return $sablons->deleteByCat($parentID) && $sablons->getProductsModel()->deleteByCat($parentID);
	}

	public function checkPresetsExists($catID)
	{
		return ($this->db->count($this->categoryPresetsTable, ['catID', $catID]) > 0) ? true : false;
	}
}