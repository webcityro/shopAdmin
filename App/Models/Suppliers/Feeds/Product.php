<?php
namespace Storemaker\App\Models\Suppliers\Feeds;
use Storemaker\System\Libraries;

class Product extends Model {
	private $productsSsupplierTable;

	function __construct() {
		parent::__construct();

		$this->productsSsupplierTable = config::get('database/prefix').'product_suppliers';
	}

	public function getTable() {
		return $this->productsSsupplierTable;
	}
}