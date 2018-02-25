<?php

class cronModel extends feedsModel {

	function __construct() {
		parent::__construct();
	}

	public function getFeeds() {
		$stx = $this->db->query("SELECT * FROM ".$this->feedsTable." GROUP BY supplierID, id HAVING active='1' AND runned='0' AND connectionStatus='1'");
		return ($stx->getNumRows() > 0) ? (($stx->getNumRows() == 1) ? [$stx->results()] : $stx->results()) : false;
	}
}