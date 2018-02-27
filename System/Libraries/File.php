<?php
namespace Storemaker\System\Libraries;

class File {
	public static function get($path) {
		$options = [
			'ssl' => [
	        'verify_peer' => false,
	        'verify_peer_name' => false
	        ]
	    ];
		return file_get_contents($path, false, stream_context_create($options));
	}

	public static function make($path, $data) {
		return file_put_contents($path, $data);
	}

	public static function cmod($file, $chmod) {
		return chmod($file, $chmod);
	}

	public static function remove($file) {
		if (file_exists($file)) {
			self::cmod($file, 0777);
			return unlink($file);
		}
	}
}