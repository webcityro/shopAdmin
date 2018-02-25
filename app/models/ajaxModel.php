<?php

class ajaxModel extends mainModel {

	function __construct() {
		parent::__construct();
	}

	public function getAutosuggest($table, $column, $columnID, $value) {
		$suggestions = $this->db->select($table, $columnID.', '.$column, [$column, 'LIKE', '%'.$value.'%']);

		return ($suggestions->getNumRows() > 0) ? (is_array($suggestions->results()) ? $suggestions->results() : [$suggestions->results()]) : false;
	}
}