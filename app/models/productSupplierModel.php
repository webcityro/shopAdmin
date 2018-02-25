<?php

class productSupplierModel extends mainModel {
	private $productsSsupplierTable;

	function __construct() {
		parent::__construct();

		$this->productsSsupplierTable = config::get('database/prefix').'product_suppliers';
	}

	public function getTable() {
		return $this->productsSsupplierTable;
	}
}