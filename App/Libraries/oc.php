<?php

class oc {
	private static $db;
	private static $manufacturerTable;
	private static $weightClassTable;
	private static $weightClassDescriptionTable;
	private static $lengthClassTable;
	private static $lengthClassDescriptionTable;
	private static $settingTable;
	private static $config;

	public static function init() {
		self::$manufacturerTable = self::getDbPrexif().'manufacturer';
		self::$weightClassTable = self::getDbPrexif().'weight_class';
		self::$weightClassDescriptionTable = self::getDbPrexif().'weight_class_description';
		self::$lengthClassTable = self::getDbPrexif().'length_class';
		self::$lengthClassDescriptionTable = self::getDbPrexif().'length_class_description';
		self::$settingTable = self::getDbPrexif().'setting';

		self::$db = database::init();
		self::$config = self::getSetting('config');
	}

	public static function getManufacturersTable()	{
		return self::$manufacturerTable;
	}

	public static function getLanguageID()	{
		return 1;
	}

	public static function getStoreID()	{
		return 0;
	}

	public static function getManufacturers() {
		return self::$db->select(self::$manufacturerTable, '*', '', 'sort_order ASC')->results();
	}

	public static function getWeightClass() {
		return self::$db->query("SELECT oc_wgtc.weight_class_id as id, oc_wgtc.value, oc_wgtcd.title, oc_wgtcd.unit
								 FROM ".self::$weightClassTable." oc_wgtc
								 RIGHT JOIN ".self::$weightClassDescriptionTable." oc_wgtcd
								 ON oc_wgtc.weight_class_id = oc_wgtcd.weight_class_id
								 WHERE language_id = :languageID", ['languageID' => self::getLanguageID()])->results();
	}

	public static function getLengthClass() {
		return self::$db->query("SELECT oc_lenc.length_class_id as id, oc_lenc.value, oc_lencd.title, oc_lencd.unit
								 FROM ".self::$lengthClassTable." oc_lenc
								 RIGHT JOIN ".self::$lengthClassDescriptionTable." oc_lencd
								 ON oc_lenc.length_class_id = oc_lencd.length_class_id
								 WHERE language_id = :languageID", ['languageID' => self::getLanguageID()])->results();
	}

	public static function getSetting($code, $storeID = 0) {
		$settingData = [];

		$setting = self::$db->select(self::$settingTable, '*', ['store_id', '=', $storeID, 'AND', 'code', '=', $code]);
		$results = ($setting->getNumRows() > 1) ? $setting->results() : [$setting->results()];

		foreach ($results as $settingRow) {
			if (!$settingRow->serialized) {
				$settingData[$settingRow->key] = $settingRow->value;
			} else {
				$settingData[$settingRow->key] = json_decode($settingRow->value, true);
			}
		}

		return $settingData;
	}

	public static function getConfig($key) {
		$key = 'config_'.$key;
		$config = self::$config;
		$keys = explode('/', $key);

		foreach ($keys as $key) {
			$config = $config[$key];
		}
		return $config;
	}

	public static function getImagesDIR() {
		return DIR_IMAGE;
	}

	public static function getDbPrexif() {
		return DB_PREFIX;
	}

	public static function getStoteName() {
		return 'demo';
	}

	public static function getProductsImageDIR() {
		return 'catalog/'.self::getStoteName().'/';
	}

	public static function getProductsImageThumbDIR() {
		return 'cache/catalog/'.self::getStoteName().'/';
	}
}