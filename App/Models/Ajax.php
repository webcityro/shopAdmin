<?php
namespace Storemaker\App\Models;
use Storemaker\System\Libraries;

class Ajax extends Model {

	function __construct() {
		parent::__construct();
	}

	public function getAutosuggest($table, $column, $columnID, $value) {
		$suggestions = $this->db->select($table, $columnID.', '.$column, [$column, 'LIKE', '%'.$value.'%']);

		return ($suggestions->getNumRows() > 0) ? (is_array($suggestions->results()) ? $suggestions->results() : [$suggestions->results()]) : false;
	}
}