<?php
namespace Storemaker\System\Libraries;

class Language {
	private static $language,
						$code,
						$directory,
						$image,
						$languageArray = array();
	protected static $db;

	public static function init() {
		self::$db = database::init();

		if (empty(self::$language)) {
			$languageID = (cookie::check('languageID')) ? cookie::get('languageID') : config::get('system/languageID');
			$lang = self::$db->select(config::get('database/prefix').'languages', '*', $languageID, '', 1);

			if ($lang->getNumRows() == 1) {
				$lang = $lang->results();
				self::$language = $lang->name;
				self::$code = $lang->code;
				self::$directory = $lang->directory;
				self::$image = $lang->image;
			} else {
				self::$language = 'Romana';
				self::$code = 'RO_ro';
				self::$directory = 'ro';
				self::$image = 'RO_ro.png';
			}

			self::load('global');
		}
	}

	public static function set($lang) {
		cookie::set('languageID', $lang, 365*12*24*60*60);
	}

	public static function load($file) {
		$path = config::get('path/app').'languages/'.self::$directory.'/'.$file.'.php';

		if (is_readable($path)) {
			require_once $path;
			self::$languageArray = array_merge(self::$languageArray, $language);
		}
	}

	public static function translate($key) {
		if (isset(self::$languageArray[$key])) {
			$str = self::$languageArray[$key];
			$argsNr = func_num_args();

			if ($argsNr > 1) {
				$args = func_get_args();

				for ($x=1; $x < $argsNr; $x++) {
					$str = str_replace('{s'.$x.'}', $args[$x], $str);
				}
			}

			return $str;
		}

		return NULL;
	}

	public static function getJSON() {
		return json_encode(self::$languageArray);
	}
}