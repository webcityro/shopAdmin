<?php

class storesModel extends mainModel {
	private $storesTable;

	function __construct() {
		parent::__construct();
		$this->storesTable = config::get('database/prefix').'stores';
	}

	public function get() {
		$url = ((!empty($_SERVER['HTTPS']) && $_SERVER['HTTPS'] != 'off') ? 'https://' : 'http://') . str_replace('www.', '', $_SERVER['HTTP_HOST']) . rtrim(dirname($_SERVER['PHP_SELF']), '/.\\');
		$store = $this->db->select($this->storesTable, 'id, name', ['url', $url], '', 1);
		return ($store->getNumRows() == 1) ? $store->results()[0] : false;
	}

	public function setIntoConfig()	{
		$store = $this->get();

		if ($store) {
			config::set('store/id', $store->id);
			config::set('store/name', $store->name);
		} else {
			config::set('store/id', '0');
			config::set('store/name', 'default');
		}
	}

	public function delete($id)	{
		return $this->db->delete($this->storesTable, $id);
	}
}