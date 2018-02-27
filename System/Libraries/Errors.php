<?php
namespace Storemaker\System\Libraries;

class Errors {
	private static $errors = array();

	public static function error404($msg) {
		$error = new error404();
		$error->index($msg);
		exit();
	}

	public static function systemError($msg) {
		$error = new systemError();
		$error->index($msg);
	}

	public static function setError(array $error)	{
		foreach ($error as $k => $v) {
			self::$errors[$k] = $v;
		}
	}

	public static function getError($type = false, $key = NULL) {
		$output = '';

		if (!$type) {
			foreach (self::$errors as $k => $v) {
				$output .= '<p>'.$v.'</p>';
			}
		} elseif ($type == 'array') {
			$output = self::$errors;
		} elseif ($type == 'key') {
			$output = self::$errors[$key];
		}

		return $output;
	}
}