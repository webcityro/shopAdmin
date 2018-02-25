<?php

class addedPriceModel extends mainModel {
	private $addedPriceTable;

	function __construct() {
		parent::__construct();
		$this->addedPriceTable = config::get('database/prefix').'added_price';
	}

	public function get() {
		$stx = $this->db->select($this->addedPriceTable, '*');
		return ($stx->getNumRows() > 0) ? (($stx->getNumRows() == 1) ? [$stx->results()] : $stx->results()) : false;
	}

	public function getPrecent($value) {
		$stx = $this->db->select($this->addedPriceTable, 'precent', ['min', '>=', $value, 'AND', 'max', '>=', $value], '', 1);
		return ($stx->getNumRows() == 1) ? $stx->results()->precent : false;
	}

	public function add($min, $max, $precent) {
		return ($this->db->insert($this->addedPriceTable, ['min' => $min, 'max' => $max, 'precent' => $precent])) ? $this->db->getLastInsertID() : false;
	}

	public function update($id, $min, $max, $precent) {
		return ($this->db->update($this->addedPriceTable, ['min' => $min, 'max' => $max, 'precent' => $precent], $id)) ? $id : false;
	}

	public function delete($id)	{
		return $this->db->delete($this->addedPriceTable, $id);
	}
}