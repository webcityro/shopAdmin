<?php
namespace Storemaker\App\Libraries\Feeds\Parsers;
use Storemaker\App\Contracts\Feeds;

class ParseExplode implements FeedParser {
	public $type = 'Explode';
	private $contents,
			$structure;

	function __construct($contents, $structure) {
		if ($contents == 'getName') {
			return;
		}
		$this->contents = json_decode($contents);
		$this->structure = $structure;
	}

	public function run($addFieldCB, $errorCB, $structure = false, $contents = false) {

	}
}