<?php
namespace Storemaker\App\Libraries\Feeds\Parsers;
use Storemaker\App\Contracts\Feeds;

class ParseCSV extends Parser implements FeedParser {
	public $type = 'CSV';
	private $contents,
		 	$structure;

	function __construct($contents, $structure) {
		if ($contents == 'getName') {
			return;
		}
		$this->contents = str_getcsv($contents, "\n");
		$this->structure = $structure;
	}

	public function run($structure = false, $contents = false) {
		$csv = ($contents) ?: $this->contents;
		$structure = ($contents) ?: $this->structure;

		foreach ($structure as $elementKey => $element) {
			$path = $this->parsePath($element->path);

			if ($path['path'] == 'all') {
				foreach ($csv as $key => $csvChild) {
					$this->parseRow($csvChild, $element, $key);

					if ($this->stop) {
						break;
					}
				}
			} else {
				$this->parseRow($csv[$path['path']], $element, $path['path']);
			}
		}
	}

	private function parseRow($csv, $element, $key)	{
		$csv = str_getcsv($csv, ";");

		if (!empty((array) $element->this) ) {
			if (!$this->findItems($element->this, (object)$csv, $key)) {
				return false;
			}

			if ($this->stop) {
				return;
			}
		}

		if (!empty((array)$element->items)) {
			foreach ($element->items as $itemKey => $item) {
				if (empty($csv[$itemKey])) {
					$this->setError(['fieldNotFound' => $itemKey]);
					continue;
				}
				$parseCSV = new \stdClass();

				$parseCSV->field = $csv[$itemKey];

				if (!$this->findItems($item, $parseCSV, 'field')) {
					continue;
				}
			}
		}
	}
}