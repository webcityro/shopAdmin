<?php
namespace Storemaker\System\Libraries;

class Model {
	protected $db;

	function __construct() {
		$this->db = Database::init();
	}
}