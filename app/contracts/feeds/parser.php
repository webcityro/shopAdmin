<?php

interface feedParser {
	function __construct($contents, $structure);
	public function run($structure, $contents);
}