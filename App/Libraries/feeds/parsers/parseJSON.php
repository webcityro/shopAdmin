<?php
namespace Storemaker\App\Libraries\Feeds\Parsers;
use Storemaker\App\Contracts\Feeds;

class ParseJSON extends Parser implements FeedParser {
	public $type = 'JSON';
	private $contents,
			$structure;

	function __construct($contents, $structure) {
		if ($contents == 'getName') {
			return;
		}
		$this->contents = (is_object($contents) || is_array($contents)) ? $contents : json_decode($contents);
		$this->structure = $structure;
	}

	public function run($structure = false, $contents = false, $all = false) {
		$structure = ($structure) ? $structure : $this->structure;
		$json = ($contents) ? $contents : $this->contents;
		$x = 0;

		// print_r($structure);
		// print_r($json);
		// die();

		foreach ($structure as $elementKey => $element) {
			$path = $this->parsePath($element->path);

			if ((!isset($json->{$path['path']}) && $path['path'] != 'all') || ($path['index'] !== false && !isset($json[$path->{$path['path']}['index']]))) {
				continue;
			}

			$thisJson = ($path['path'] == 'all') ? $json : ((is_array($json)) ? $json[$path['path']] : ($path['index'] !== false) ? $json->{$path['path']}[$path['index']] : $json->{$path['path']});

			if (!empty($element->this)) {
				$this->thisItems($json, $element);
				continue;
			}

			if ($path['path'] == 'all' && is_array($thisJson)) {
				foreach ($thisJson as $key => $child) {
					$this->items($child, $element);
					if (property_exists($element, 'items') && property_exists($element->items, 'all')) {
						continue;
					}
					$this->children($child, $element);
				}
				continue;
			}

			$this->items($thisJson, $element);

			if (property_exists($element, 'items') && property_exists($element->items, 'all')) {
				continue;
			}
			$this->children($thisJson, $element);
		} // structure
		$x++;
	}

	private function items($json, $element) {
		if (property_exists($element, 'items') && !empty((array) $element->items)) {
			$path = $this->parsePath($element->path);

			foreach ($element->items as $key => $item) {
				if ($key == 'all') {
					$this->allItems($json, $element);
					continue;
				}

				if (!isset($json->{$key}) && $path['path'] != 'all') {
					$this->setError(['fieldNotFound' => $key]);
					continue;
				}

				if (!$this->findItems($item, $json, $key)) {
					continue;
				}

				if ($this->stop) {
					break;
				}
			}
			return true;
		}
		return false;
	}

	private function thisItems($json, $element) {
		foreach ($json as $key => $child) {
			if (!$this->findItems($element->this, $json, $key)) {
				continue;
			}
			$this->items($child, $element);
			$this->children($child, $element);

			if ($this->stop) {
				break;
			}
		}
	}

	private function allItems($json, $element) {
		foreach ($json as $key => $child) {
			if (!$this->findItems($element->items->all, $json, $key)) {
				continue;
			}

			$this->children($child, $element);

			if ($this->stop) {
				break;
			}
		}
	}

	private function children($json, $element) {
		if (property_exists($element, 'children') && !empty((array)$element->children)) {
			$this->run($element->children, $json);
		}
	}
}