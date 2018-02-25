<?php
namespace feeds\parsers;
require_once \config::get('path/appContracts').'feeds/parser.php';

class parseExplode implements \feedParser {
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