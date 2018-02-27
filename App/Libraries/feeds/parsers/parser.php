<?php
namespace Storemaker\App\Libraries\Feeds\Parsers;

class Parser {
	protected $ignoreList = [],
				 $contentFile,
				 $productsArr = [],
				 $productsDefaultArr = ['fields' => [], 'attributes' => [], 'images' => []],
				 $productIndex = 0,
				 $attributeName,
				 $attributeValue,
				 $custonIndex,
				 $limit = false,
				 $count = 0,
				 $stop = false;

	private $errors = [];

	function __construct() {

	}

	public function getProducts()	{
		$products = $this->productsArr;
		$this->productsArr = [];
		return $products;
	}

	public function setProducts($productsArr) {
		$this->productsArr = $productsArr;
	}

	public function getErrors() {
		return $this->errors;
	}

	public function setError($error) {
		$this->errors[] = $error;
	}

	public function setContentsFile($path)	{
		$this->contentFile = $path;
	}

	public function setLimit($limit)	{
		$this->limit = $limit;
	}

	private function checkIgnore($item, $content) {
		if (!empty($item->ignore) && $item->ignore) {
			$this->ignoreList[] = $content;
			return true;
		}
		if (in_array($content, $this->ignoreList, true)) {
			return true;
		}
		return false;
	}

	protected function findItems($item, $content, $contentKey, $test = false) {
		if (property_exists($item, 'text')) {
			if ($this->checkIgnore($item->text, $content)) {
				return false;
			}
			$this->fetchItem($item->text, $contentKey);
		}

		if (property_exists($item, 'value') || property_exists($item, 'attributeXMLValue')) {
			if ($test) {
				echo '<br>key='. $contentKey.'<br>';
				print_r($item);
				print_r($content);
				var_dump($content->{$contentKey});
				// die();
			}

			if (!property_exists((object)$content, $contentKey)) {
				$this->setError(['fieldNotFound' => ['key' => $contentKey, 'item' => $item]]);
				return false;
			}

			$content = $content->{$contentKey};
		}

		if (property_exists($item, 'attributeXMLValue')) {
			foreach ($item->attributeXMLValue as $attributeName => $items) {
				/*if ($this->checkIgnore($items, $content->{$key})) {
					return false;
				}*/
				if (!isset($content->attributes()[$attributeName])) {
					$this->setError(['xmlAttributeNotFound' => ['attributeName' => $attributeName, 'key' => $contentKey, 'item' => $item]]);
					continue;
				}
				$this->fetchItem($items, (string) $content->attributes()[$attributeName]);
			}
		}

		if (property_exists($item, 'value')) {
			if ($this->checkIgnore($item->value, $content)) {
				return false;
			}
			$this->fetchItem($item->value, (string) $content);
		}
		return true;
	}

	protected function fetchItem($item, $value) {
		$value = trim(strip_tags($value));

		if (!empty($item->product) && $item->product) {
			if ($this->limit && $this->count >= $this->limit) {
				$this->stop = true;
				return;
			}

			if (!empty($this->productsArr[$this->productIndex]['fields']) ) {
				$this->count++;
			}
			$this->productIndex = (empty($this->productsArr[$this->productIndex]['fields'])) ? $this->productIndex : count($this->productsArr);
			$this->custonIndex = false;
			$this->productsArr[$this->productIndex] = $this->productsDefaultArr;

		}

		if (!empty($item->commonField) && $item->commonField) {
			$tmpProductArr = $this->productsArr[$this->productIndex];
			unset($this->productsArr[$this->productIndex]);
			$this->productIndex = 'common_'.$value;

			if (isset($this->productsArr[$this->productIndex])) {
				array_merge_recursive($this->productsArr[$this->productIndex], $tmpProductArr);
			} else {
				$this->productsArr[$this->productIndex] = $tmpProductArr;
			}
		}

		if (!empty($item->image) && $item->image && !empty($value)) {
			$this->productsArr[$this->productIndex]['images'][] = $value;
		}

		if (!empty($item->attribute)) {
			if ($item->attribute == 'name') {
				$this->attributeName = $value;
			} else if ($item->attribute == 'value') {
				$this->attributeValue = $value;
			}

			if (!empty($this->attributeName) && !empty($this->attributeValue)) {
				$this->productsArr[$this->productIndex]['attributes'][] = ['name' => $this->attributeName, 'value' => $this->attributeValue];
				$this->attributeName = '';
				$this->attributeValue = '';
			}
		}

		if (!empty($item->field) ||
			(!empty($item->productLink) && $item->productLink) ||
			(!empty($item->category) && $item->category)) {
			$fieldKey = (!empty($item->field)) ? $item->field : ((!empty($item->productLink)) ? 'productLink' : 'category');
			$this->productsArr[$this->productIndex]['fields'][$fieldKey] = $value;
		}

		if (!empty($item->explode)) {
			$itemsArr = explode($item->explode->spliter, $value);

			if (empty($itemsArr)) {
				return true;
			}

			foreach ($item->explode->items as $key => $fields) {
				if ($key == 'all') {
					foreach ($itemsArr as $e) {
						$this->fetchItem($fields, $e);
					}
					continue;
				}
				$index = str_replace('index_', '', $key);

				if (isset($itemsArr[$index])) {
					$this->fetchItem($fields, $itemsArr[$index]);
				}
			}
		}
		return true;
	}

	protected function parsePath($path)	{
		if (preg_match('/\:\([0-9]+\)/', $path)) {
			$pathArr = explode(':(', $path);
			return ['path' => $pathArr[0], 'index' => str_replace(')', '', $pathArr[1])];
		}
		return ['path' => $path, 'index' => false];
	}
}