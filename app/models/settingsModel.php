<?php

class settingsModel extends mainModel {
	private $settingsTable;

	function __construct() {
		parent::__construct();
		$this->settingsTable = config::get('database/prefix').'settings';
	}

	public function get() {
		$stx = $this->db->select($this->settingsTable, '*', ['storeID', config::get('store/id')]);
		return ($stx->getNumRows() > 0) ? $stx->results() : false;
	}

	public function setIntoConfig()	{
		$settings = $this->get();

		if ($settings) {
			foreach ($settings as $row) {
				config::set($row->code.'/'.$row->key, ($row->serialized == '1') ? json_decode($row->value) : $row->value);
			}
		}
	}

	public function delete($id)	{
		return $this->db->delete($this->settingsTable, $id);
	}
}