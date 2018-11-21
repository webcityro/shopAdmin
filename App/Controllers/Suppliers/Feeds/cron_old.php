<?php

class cron extends feeds {
	private $feedsArr = [],
			$productsArr = [],
			$productsDefaultArr = ['fields' => [], 'attributes' => [], 'images' => []],
			$aliases,
			$errors,
			$productSupplier,
			$addedPrice,
			$productIndex = 0,
			$attributeName,
			$attributeValue,
			$custonIndex;

	function __construct() {
		set_time_limit(1000);
		ini_set('memory_limit', '-1');
		ini_set('max_execution_time', 0);
		ini_set('xdebug.max_nesting_level', 2000);
		parent::__construct(false);

		$this->productSupplier = new productSupplierModel();
		$this->aliases = new aliasesModel();
		$this->errors = new errorHandleModel();
		$this->addedPrice = new addedPriceModel();

		$this->errors->email(true);
		echo "<pre>";
	}

	public function index2() {
		$feeds = $this->model->getFeeds();

		foreach ($feeds as $row) {
			$this->feedsArr[$row->supplierID] = (empty($this->feedsArr[$row->supplierID])) ? [] : $this->feedsArr[$row->supplierID];
			$this->feedsArr[$row->supplierID][$row->runAfterID] = $row;
		}
		// print_r($this->feedsArr);
		$this->loopThrewSuppliers();
	}

	private function loopThrewSuppliers() {
		foreach ($this->feedsArr as $supplierFeeds) {
			$this->parse($supplierFeeds, 0);
			// die(print_r($this->productsArr, 1));
		}
		die(print_r($this->productsArr, 1));
	}

	private function parse($feeds, $runAfterID) {
		// $feed = $feeds[5];
		$feed = $feeds[$runAfterID];
		echo "<h3>".$feed->name.'</h3>';
		// print_r(json_decode($feed->structure));
		// die();
		$contents = $this->login($feed->loginType,
			$feed->loginUserName,
			$feed->loginPassword,
			$feed->loginCURLusernameField,
			$feed->loginCURLpasswordField,
			$feed->loginSoapVars,
			$feed->loginSoapClientArgs,
			$feed->loginSoapLoginFunction,
			$feed->loginSoapResultsFunction,
			$feed->loginURL,
			$feed->mainURL);
		// $contents = file_get_contents(config::get('path/temp').'tmp_feeds/it_direct.json');
			// file_put_contents(config::get('path/temp').'tmp_feeds/soliton.csv', $contents);
			// die();
			// $contents = file_get_contents(config::get('path/temp').'tmp_feeds/soliton.csv');

		if (!$contents || empty($contents)) {
			$this->setLoginError($feed);
			return;
		} else if (empty($feed->structure)) {
			return;
		}

		$fileContents = $this->model->makeFeedTempFile($contents, 'feed_'.$feed->id.'.'.$this->getExt($feed->type));
		$structure = json_decode($feed->structure);
		$parserClass = 'feeds\\parsers\\parse'.$feed->type;
		$_this = $this;
		$parser = new $parserClass($contents, $structure);

		$parser->setContentsFile(($fileContents) ? config::get('path/temp').$fileContents : false);

		if ($runAfterID > 0) {
			// print_r($structure);
			// print_r($contents);
			// die();
			$parser->notFirst = true;
		}

		$parser->setFieldCallback(function($field) use($_this) {
			# field finded
			$productIndex = ($this->custonIndex) ?: $this->productIndex;

			if (isset($field['product'])) {
				$this->productIndex = count($this->productsArr);
				$_this->custonIndex == false;

				$this->productsArr[$this->productIndex] = $this->productsDefaultArr;
			} else if (isset($field['commonField'])) {
 				// die($field['commonField']);
 				/*if (!isset($this->productsArr[$this->productIndex])) {
 					echo "<br>productIndex = $this->productIndex<br>";
 					print_r($this->productsArr);
 					die();
 				}*/
				$tmpProductArr = $this->productsArr[$this->productIndex];
				unset($this->productsArr[$this->productIndex]);
				/*$_this->custonIndex = 'common_'.$field['commonField'];

				$this->productsArr[$_this->custonIndex] = (isset($this->productsArr[$_this->custonIndex])) ? array_merge_recursive($this->productsArr[$_this->custonIndex], $tmpProductArr) : $tmpProductArr;*/
				$this->productIndex = 'common_'.$field['commonField'];
				if (isset($this->productsArr[$this->productIndex])) {
					array_merge_recursive($this->productsArr[$this->productIndex], $tmpProductArr);
				} else {
					$this->productsArr[$this->productIndex] = $tmpProductArr;
				}
			} else if (isset($field['attribute'])) {
				// print_r($field['attribute']);
				// die();
				if (isset($field['attribute']['name'])) {
					$this->attributeName = $field['attribute']['name'];
				} else if (isset($field['attribute']['value'])) {
					$this->attributeValue = $field['attribute']['value'];
				}

				if (!empty($this->attributeName) && !empty($this->attributeValue)) {
					$this->productsArr[$productIndex]['attributes'][] = ['name' => $this->attributeName, 'value' => $this->attributeValue];
					// echo $this->attributeName, ' => ', $this->attributeValue, '</br>';
					$this->attributeName = '';
					$this->attributeValue = '';
					// echo "<br>", $productIndex;
					// print_r($this->productsArr[$productIndex]);
					// die();
				}
			} else if (isset($field['image'])) {
				$this->productsArr[$productIndex]['images'][] = $field['image'];
			} else {
				$fieldKey = (isset($field['field'])) ? array_keys($field['field'])[0] : array_keys($field)[0];
				$this->productsArr[$productIndex]['fields'][$fieldKey] = (isset($field['field'])) ? $field['field'][$fieldKey] : $field[$fieldKey];
			}
		});

		$parser->seterrorCallback(function($field) use($_this) {
			# field not finded
			// print_r($field);
		});

		$parser->run();

		if (isset($feeds[$feed->id])) {
			$this->parse($feeds, $feed->id);
		}
	}

	private function setLoginError($feed) {

	}
}