<?php
namespace Storemaker\System\Libraries;

class Config {
	private $configArray = [];

	public function __construct(array $config) {
		$this->configArray = $config;
	}

	public function set($key, $value, $value2 = false) {
		$keys = explode('/', $key);
		$k = array_shift($keys);
		$configArray = ($value2 !== false) ? $value2 : $this->configArray;

		if (!isset($configArray[$k])) {
			$configArray[$k] = [];
		}

		if (count($keys) == 0) {
			$configArray[$k] = $value;
		} else if ($value2 === false) {
			$this->configArray[$k] = $this->set(implode('/', $keys), $value, $configArray[$k]);
		} else {
			$configArray[$k] = $this->set(implode('/', $keys), $value, $configArray[$k]);
		}
		return $configArray;
	}

	public function get($key) {
		$value = $this->configArray;

		foreach (explode('/', $key) as $k) {
			if (isset($value[$k])) {
				$value = $value[$k];
			}
		}

		return $value;
	}

	public function dump() {
		echo "<pre>", print_r($this->configArray, true), "</pre>";
	}
}