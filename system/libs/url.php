<?php
class url {
	private static $segments = [];
	private static $paginationSegments = ['page' => 1];
	private static $segmentsNum = 0;
	private static $paginationCount = -1;

	public static function init() {
		self::_setSegments();
		self::_setPaginationSegments();
		self::$segmentsNum = count(self::$segments);
	}

	private static function _setSegments() {
		$url = (!empty($_GET['url'])) ? explode('/', rtrim(filter_var($_GET['url'], FILTER_SANITIZE_URL), '/')) : 'index';
		self::$segments = ($url == 'index') ?['index'] : $url;
	}

	private static function _setPaginationSegments() {
		foreach (self::$segments as $key => $segment) {
			if ((substr($segment, 0, 4) == 'page') && ((substr($segment, 4, 1) == '-') || (ctype_digit(substr($segment, 4, 1)) && substr($segment, 5, 1) == '-'))) {
				$kbum = explode('-', $segment);
				self::$paginationSegments['page'.(($kbum[0] > 0) ? $kbum[0] : '')] = $kbum[1];
				unset(self::$segments[$key]);
			}
		}
	}

	public static function getPaginationNr() {
		self::$paginationCount++;
		$key = (self::$paginationCount == 0) ? 'page' : 'page'.self::$paginationCount;
		return (!empty(self::$paginationSegments[$key])) ? self::$paginationSegments[$key] : 1;
	}

	public static function setPaginationNr($pnr) {
		return config::get('site/domain').implode('/', self::$segments).'/page'.((self::$paginationCount > 0) ? self::$paginationCount : '').'-'.$pnr;
	}

	public static function getNumSegments() {
		return self::$segmentsNum;
	}

	public static function getSegment($key) {
		if (!is_numeric($key)) {
			if (in_array($key, self::$segments)) {
				$key = array_search($key, self::$segments)+1;
			} else return false;
		} else {
			$key--;
		}
		return (isset(self::$segments[$key])) ? self::$segments[$key] : false;
	}

	public static function inURL($value) {
		return in_array($value, self::$segments);
	}

	public static function redirect($url, $k='', $v='', $refresh = true) {
		if (!empty($k) && !empty($v)) {
			if (in_array($k, self::$segments)) {
				self::$segments[array_search($k, self::$segments) +1] = $v;
			} else {
				self::$segments[] = $k;
				self::$segments[] = $v;
			}
			$redirect = config::get('site/domain').implode('/', self::$segments);
		} else {
			$redirect = config::get('site/domain').$url;
		}
		if ($refresh) {
			header('Location: '.$redirect);
		} else {
			return $redirect;
		}
	}
}