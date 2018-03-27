<?php
namespace Storemaker\System\Libraries;

class Language {
	private $language,
			  $code,
			  $directory,
			  $image,
			  $languageArray = [];
	protected $container;

	public function __construct($container) {
		$this->container = $container;

		if (empty($this->language)) {
			$languageID = (isset($_COOKIE['languageID'])) ? $_COOKIE['languageID'] : $this->container->config->get('system/languageID');

			$lang = $this->container->db->table('languages')->where('id', $languageID)->first();

			if ($lang) {
				$this->language = $lang->name;
				$this->code = $lang->code;
				$this->directory = $lang->directory;
				$this->image = $lang->image;
			} else {
				$this->language = 'Romana';
				$this->code = 'RO_ro';
				$this->directory = 'ro';
				$this->image = 'RO_ro.png';
			}

			$this->load('global');
		}
	}

	public function set($lang) {
		cookie::set('languageID', $lang, 365*12*24*60*60);
	}

	public function load($file) {
		$path = $this->container->config->get('path/app').'languages/'.$this->directory.'/'.$file.'.php';

		if (is_readable($path)) {
			require_once $path;
			$this->languageArray = array_merge($this->languageArray, $language);
		}
	}

	public function translate($key) {
		if (isset($this->languageArray[$key])) {
			$str = $this->languageArray[$key];
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

	public function getJSON() {
		return json_encode($this->languageArray);
	}
}