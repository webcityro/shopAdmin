<?php

class categoriesObj {
	private static $db,
				   $tamplate = [],
				   $categoryModel;

	public static function init () {
		self::$db = database::init();
		self::$categoryModel = categoriesModel::getInstance();
	}

	public static function getByParent($parentID = 0) {
		$stx = self::$db->query("SELECT oc_cat.category_id, oc_cat.parent_id, oc_cat.image, oc_cat.sort_order, oc_catd.name
								FROM ".self::$categoryModel->getCategoryTable()." oc_cat
								RIGHT JOIN ".self::$categoryModel->getCategoryDescriptionTable()." oc_catd
								ON oc_cat.category_id = oc_catd.category_id
								WHERE oc_cat.parent_id = :id
								ORDER BY oc_cat.sort_order", ['id' => $parentID]);
		return ($stx->getNumRows() > 0) ? ((!is_array($stx->results())) ? [$stx->results()] : $stx->results()) : (false);
	}

	public static function setTamplate($tamplate) {
		self::$tamplate = $tamplate;
	}

	public static function render($callback = false, $parentID = 0) {
		$return = ($parentID != 0) ? str_replace('{{parent_id}}', $parentID, self::$tamplate['start']) : '';
		$cats = self::getByParent($parentID);

		if ($cats) {
			foreach ($cats as $catRow) {
				$catRow->image = (!empty($catRow->image)) ? 'cache/'.$catRow->image : 'no_image.png';
				$tamplate = self::processTamplate($callback, $catRow);
				$return .= $tamplate['repeat'];
			}
		}

		if ($callback) {
			$return .= $callback($parentID);
		}

		$return .= ($parentID != 0) ? str_replace('{{parent_id}}', $parentID, self::$tamplate['end']) : '';
		return $return;
	}

	private static function processTamplate($callback, $row) {
		$tamplate = self::$tamplate;

		foreach (self::$tamplate as $key => $tpl) {
			$tamplate[$key] = preg_replace_callback('/\{\{(.*?)\}\}/', function($matche) use($row) {
				if ($matche[1] == 'subcats') {
					return $matche[0];
				}
				return $row->{$matche[1]};
			}, $tpl);
		}
		$tamplate['repeat'] = str_replace('{{subcats}}', self::render($callback, $row->category_id), $tamplate['repeat']);
		return $tamplate;
	}
}