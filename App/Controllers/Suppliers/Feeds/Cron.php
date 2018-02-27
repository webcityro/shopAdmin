<?php
namespace Storemaker\App\Controllers\Suppliers\Feeds;

use Storemaker\App\Controllers;

class Cron extends Feeds {
	private $feedsArr = [],
			  $aliases,
			  $errors,
			  $productSupplier,
			  $addedPrice,
			  $supplierProductsArray = [];

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
		}
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

		$fileContents = $this->model->makeFeedTempFile((is_array($contents) || is_object($contents)) ? json_encode($contents) : $contents, 'feed_'.$feed->id.'.'.$this->getExt($feed->type));
		$structure = json_decode($feed->structure);
		$parserClass = 'feeds\\parsers\\parse'.$feed->type;
		$parser = new $parserClass($contents, $structure);

		$parser->setContentsFile(($fileContents) ? config::get('path/temp').$fileContents : false);
		// $parser->setLimit(4);
		// print_r($structure);
		// die();

		if ($runAfterID > 0) {
			$parser->setProducts($this->supplierProductsArray[$feed->supplierID]);
			$parser->notFirst = true;
		}

		$parser->run();
		$this->supplierProductsArray[$feed->supplierID] = $parser->getProducts();
		$this->setFeedError($parser->getErrors());

		if (isset($feeds[$feed->id])) {
			$this->parse($feeds, $feed->id);
		} else {
			print_r($this->supplierProductsArray[$feed->supplierID]);
			die();
		}
	}

	private function setLoginError($feed) {

	}

	private function setFeedError($error) {
		// print_r($error);
	}
}