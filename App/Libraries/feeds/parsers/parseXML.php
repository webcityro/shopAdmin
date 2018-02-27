<?php
namespace Storemaker\App\Libraries\Feeds\Parsers;
use Storemaker\App\Contracts\Feeds;

class ParseXML extends Parser implements FeedParser {
	public $type = 'XML';
	private $contents,
			$structure;

	function __construct($contents, $structure) {
		if ($contents == 'getName') {
			return;
		}
		$this->contents = (is_object($contents)) ? $contents : new \SimpleXMLElement($contents);
		$this->structure = $structure;
	}

	public function run($structure = false, $contents = false, $test = false) {
		$structure = ($structure) ? $structure : $this->structure;
		$xml = ($contents !== false) ? $contents : $this->contents;
		$x = '0';
		// $test = true;

		foreach ($structure as $elementKey => $element) {
			$path = $this->parsePath($element->path);

			if ($contents == false && $xml->getName() == $path['path'] && empty($element->items)) {
				$this->run($element->children, $xml, $test);
				continue;
			}

			if (($contents == false && $xml->getName() == $path['path']) ||
				($path['path'] == 'all' || $xml->xpath($path['path'])) ||
				($path['index'] === false || $path['index'] == $x)) {
				$xmlItem = (($path['path'] == 'all') || ($contents == false && $xml->getName() == $path['path'])) ?
						$xml :
					(($path['index'] === false) ?
						$xml->{$path['path']}->children() :
						$xml->{$path['path']}[$path['index']]);

				if (!empty($element->this)) {
					if (!$this->findItems($element->this, $xmlItem, $xmlKey, $test)) {
						continue;
					}
				}

				if ($path['path'] == 'all') {
					if (is_array($xmlItem) && !empty((array)$element->items)) {
						$this->allItemsPath($xmlItem, $element, $test);
					}

					if (empty((array)$element->items)) {
						foreach ($xmlItem as $child) {
							$this->children($child, $element, $test);
						}
						continue;
					}
				}

				$this->items($xmlItem, $element, $test);
				$this->children($xmlItem, $element, $test);
			} else {
				$this->setError(['fieldNotFound' => $path['path']]);
			}
			$x++;
		}
	}

	private function items($xml, $element, $test)	{
		if (!empty((array)$element->items)) {
			foreach ($element->items as $key => $item) {
				if (!$xml->xpath($key) && $key != 'all') {
					$this->setError(['fieldNotFound' => $key]);
					continue;
				}

				if ($key == 'all' || is_array($xml->{$key})) {
					$loopXML = ($key == 'all') ? $xml : $xml->{$key};

					foreach ($loopXML as $k => $child) {
						$thisXML = new \stdClass();
						$thisXML->{$key} = ($key == 'all') ? $child  : $xml->{$key}[$k];

						$this->findItems($item, $thisXML, $key, $test);

						if ($key == 'all') {
							$this->children($child, $element, $test, false);
						}

						if ($this->stop) {
							break;
						}
					}
					continue;
				}
				if (!$this->findItems($item, $xml, $key, $test)) {
					continue;
				}

				if ($this->stop) {
					break;
				}
			}
		}
	}

	private function allItemsPath($xml, $element, $test) {
		if (empty($xml)) {
			return;
		}

		foreach ($xml as $child) {
			foreach ($element->items as $key => $item) {
				if (!$this->findItems($item, $child, $key, $test)) {
					continue;
				}
			}
			$this->children($child, $element, $test, false);

			if ($this->stop) {
				break;
			}
		}
	}

	private function children($xml, $element, $test, $children = true)	{
		if (!empty($element->children)) {
			$this->run($element->children, ($children) ? $xml->children() : $xml, $test);
		}
	}
}