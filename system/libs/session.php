<?php

class session {
	private static $instance;

	private function __construct() {
		session_start();
	}

	public static function init() {
		if (empty(self::$instance)) {
			self::$instance = new session();
		}
		return self::$instance;
	}

	public static function set($key, $value) {
		$_SESSION[$key] = $value;
	}

	public static function get($key) {
		return (isset($_SESSION[$key])) ? $_SESSION[$key] : false;
	}

	public static function delete($key) {
		unset($_SESSION[$key]);
	}

	public static function check($key) {
		return (isset($_SESSION[$key]));
	}

	public static function removeAll() {
		session_destroy();
	}

	public static function flash($key, $value = '')	{
		$key = 'flash'.ucfirst($key);
		if (self::check($key) && empty($value)) {
			$flash = self::get($key);
			self::delete($key);
			return $flash;
		} else {
			self::set($key, $value);
		}
	}

	public static function flashExists($key) {
		$key = 'flash'.ucfirst($key);
		return self::check($key);
	}
}