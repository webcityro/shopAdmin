<?php

class input {
	public static function exists($type = 'post') {
		if ($type == 'post') {
			return (isset($_POST)) ? true : false;
		} else if ($type == 'get') {
			return (isset($_GET)) ? true : false;
		}
	}

	public static function post($key) {
		return (isset($_POST[$key])) ? trim($_POST[$key]) : '';
	}

	public static function get($key) {
		return (isset($_GET[$key])) ? trim($_GET[$key]) : '';
	}

	public static function escape($str)	{
		return htmlentities($str, ENT_QUOTES, 'utf-8');
	}
}