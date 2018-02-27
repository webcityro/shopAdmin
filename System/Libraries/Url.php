<?php
namespace Storemaker\System\Libraries;

class Url {
	private $url,
			  $segments;

	function __construct() {
		// $this->url = filter_var(rtrim($_GET['url'], '/'), FILTER_VALIDATE_URL);
		$this->url = rtrim($_GET['url'], '/');
		// die($this->url);
		$this->segments = explode('/', $this->url);
	}

	public function getURL() {
		return $this->url;
	}

	public function getSegments() {
		return $this->segments;
	}
}