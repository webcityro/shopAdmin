<?php

class groupsModel extends mainModel {
	private $table;

	function __construct() {
		parent::__construct();
		$this->table = config::get('database/prefix').'user_groups';
	}

	public function get($id) {
		$stx = $this->db->select($this->table, '*', $id, '', 1);
		return ($stx->getNumRows() == 1) ? $stx->results() : false;
	}

	public function getAll($perPaga = 10) {
		$stx = $this->db->select($this->table, '*');
		return ($stx->getNumRows() > 0) ? (($perPaga) ? $stx->paginate($perPaga) : $stx->results()) : false;
	}

	public function checkExists($group, $id = false) {
		$field = (ctype_digit($group)) ? 'id' : 'name';
		$userQuery = $this->db->select($this->table, 'id', (($id) ? ['id', '!=', $id, 'AND', 'name', '=', $group] : [$field, $group]), '', 1);
		return ($userQuery->getNumRows() == 1) ? true : false;
	}

	public function add($name, $permissions) {
		$insert = $this->db->insert($this->table, [
			'storeID' => config::get('store/id'),
			'name' => $name,
			'permissions' => $permissions]);

		return (!$insert->error()) ? $this->db->getLastInsertID() : false;
	}

	public function update($name, $permissions, $id) {
		$update = $this->db->update($this->table, [
			'storeID' => config::get('store/id'),
			'name' => $name,
			'permissions' => $permissions], $id);

		return ($update) ? $id : false;
	}

	public function delete($id) {
		return $this->db->delete($this->table, $id, 1);
	}
}