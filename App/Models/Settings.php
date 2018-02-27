<?php
namespace Storemaker\App\Models;
use Storemaker\System\Libraries\Model;
use Storemaker\System\Libraries\Config;

class Settings extends Model {
	private $settingsTable;

	function __construct() {
		parent::__construct();
		$this->settingsTable = Config::get('database/prefix').'settings';
	}

	public function get() {
		$stx = $this->db->select($this->settingsTable, '*', ['storeID', Config::get('store/id')]);
		return ($stx->getNumRows() > 0) ? $stx->results() : false;
	}

	public function setIntoConfig()	{
		$settings = $this->get();

		if ($settings) {
			foreach ($settings as $row) {
				Config::set($row->code.'/'.$row->key, ($row->serialized == '1') ? json_decode($row->value) : $row->value);
			}
		}
	}

	public function delete($id)	{
		return $this->db->delete($this->settingsTable, $id);
	}
}