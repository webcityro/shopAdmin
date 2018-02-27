<?php
namespace Storemaker\System\Libraries;

class Dir {
	public static function create($dir, $chmod) {
		return mkdir($dir, $chmod);
	}

	public static function exists($dir) {
		return is_dir($dir);
	}

	public static function read($dir) {
		return scandir($dir);
	}

	public static function cmpd($dir) {
		return chdir($dir);
	}

	public static function remove($dir) {
		foreach (self::read($dir) as $innerDir) {
			if (!in_array($innerDir, array('.', '..'))) {
				if (self::exists($dir.'/'.$innerDir)) {
					self::cmpd($dir.'/'.$innerDir);
					self::remove($dir.'/'.$innerDir);
				} else {
					file::remove($dir.'/'.$innerDir);
				}
			}
		}

		return rmdir($dir);
	}
}