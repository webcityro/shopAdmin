<?php
namespace Storemaker\System\Libraries;

class Hash {
	public static function make($str, $salt) {
		return hash('sha256', $str.$salt);
	}

	public static function salt($length=32) {
		return mcrypt_create_iv($length);
	}

	public static function uniqe() {
		return self::salt((int)uniqid());
	}
}