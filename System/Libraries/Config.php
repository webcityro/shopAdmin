<?php
namespace Storemaker\System\Libraries;

class Config {
	private static $configArray = array();

	public static function setInitial(array $config)	{
		self::$configArray = $config;
	}

	public static function set($key, $value, $value2 = false) {
		$keys = explode('/', $key);
		$k = array_shift($keys);
		$configArray = ($value2 !== false) ? $value2 : self::$configArray;

		if (!isset($configArray[$k])) {
			$configArray[$k] = [];
		}

		if (count($keys) == 0) {
			$configArray[$k] = $value;
		} else if ($value2 === false) {
			self::$configArray[$k] = self::set(implode('/', $keys), $value, $configArray[$k]);
		} else {
			$configArray[$k] = self::set(implode('/', $keys), $value, $configArray[$k]);
		}
		return $configArray;
	}

	public static function get($key) {
		$value = self::$configArray;

		foreach (explode('/', $key) as $k) {
			if (isset($value[$k])) {
				$value = $value[$k];
			}
		}

		return $value;
	}

	public static function dump() {
		echo "<pre>", print_r(self::$configArray, true), "</pre>";
	}
}