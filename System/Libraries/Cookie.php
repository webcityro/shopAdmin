<?php
namespace Storemaker\System\Libraries;

class Cookie {
	public static function check($key) {
		return (isset($_COOKIE[$key])) ? true : false;
	}

	public static function set($key, $value, $time) {
		setcookie($key, $value, time()+$time, '/');
	}

	public static function get($key) {
		return (isset($_COOKIE[$key])) ? $_COOKIE[$key] : false;
	}

	public static function delete($key) {
		setcookie($key, '', 1, '/');
	}

	public static function removeAll() {
		foreach ($_COOKIE as $key => $value) {
			self::delete($key);
		}
	}
}