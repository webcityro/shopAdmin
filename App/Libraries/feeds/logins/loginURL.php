<?php
namespace Storemaker\App\Libraries\Feeds\Logins;
use Storemaker\App\Contracts\Feeds;
use \Curl\Curl;

class LoginURL implements FeedLogin {
	public $type = 'URL';
	protected $loginURL;
	protected $mainURL;

	public function setLoginURL($url) {
		$this->loginURL = $url;
	}

	public function setMainURL($url) {
		$this->mainURL = $url;
	}

	function run($username, $password, $p3 = '', $p4 = '') {
		$url = str_replace(['{username}', '{password}'], [$username, $password], $this->loginURL);
		$curl = new Curl();

		$curl->setOpt(CURLOPT_SSL_VERIFYHOST, 0);
		$curl->setOpt(CURLOPT_SSL_VERIFYPEER, 0);
		$curl->setConnectTimeout(60);
		$curl->setTimeout(60);
		$curl->get($url);

		if (!empty($this->mainURL)) {
			$curl->get($this->mainURL);
		}

		return ($curl->error) ? false : $curl->response;
	}
}