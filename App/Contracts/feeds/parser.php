<?php
namespace Storemaker\App\Contracts\Feeds;

interface Parser {
	function __construct($contents, $structure);
	public function run($structure, $contents);
}