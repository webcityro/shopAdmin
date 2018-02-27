<?php
namespace Storemaker\App\Libraries\Feeds\Logins;
use Storemaker\App\Contracts\Feeds;
use \Curl\Curl;

class LoginCURL implements Login {
	public $type = 'CURL';
	protected $loginURL;
	protected $mainURL;
	protected $curl;
	protected $cookieFile = 'cookie.txt';

	function __construct () {
		$this->curl = new Curl();
		$this->curl->setcookieFile($this->cookieFile);
		$this->curl->setcookieJar($this->cookieFile);
		$this->curl->setConnectTimeout(60);
		$this->curl->setTimeout(60);
	}

	public function setLoginURL($url) {
		$this->loginURL = $url;
	}

	public function setMainURL($url) {
		$this->mainURL = $url;
	}

	function run($username, $password, $p3, $p4) {
		$this->curl->setOpt(CURLOPT_SSL_VERIFYHOST, 0);
		$this->curl->setOpt(CURLOPT_SSL_VERIFYPEER, 0);
		$this->curl->post($this->loginURL, [$p3 => $username, $p4 => $password]);

		if ($this->curl->error) {
			die($this->curl->errorMessage);
			return false;
		}

		if (!empty($this->mainURL)) {
			$this->curl->get($this->mainURL);
		}
		return $this->curl->response;
	}
}