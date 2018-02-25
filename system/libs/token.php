<?php

class token {
	public static function generate($name) {
		$token = md5(uniqid());
		session::set('token_'.$name, $token);
		return $token;
	}

	public static function check($name, $token, $keep = false) {
		if (session::check('token_'.$name) && session::get('token_'.$name) === $token) {
			if (!$keep) {
				self::delete('token_'.$name);
			}
			return true;
		}
		return false;
	}

	public static function delete($name) {
		session::delete('token_'.$name);
	}
}