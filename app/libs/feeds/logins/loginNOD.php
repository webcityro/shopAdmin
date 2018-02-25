<?php
namespace feeds\logins;
use \Curl\Curl;

require_once \config::get('path/appContracts').'feeds/login.php';

/*
   node detalies:
   url: https://api.b2b.nod.ro/
   api_user / _client: eaaae73a8d24af50a2a6d58dcdd1975a
   api_key: 700615917eb64872dc7d3313589dbcbeb7a1d0e9
   methods:
   	product-categories
   	products
  url format: url/methode?order_by=&order_direction=
*/

class loginNOD implements \feedLogin {
	public $type = 'NOD';
	protected $loginURL,
			  $mainURL,
			  $curl,
			  $queryString,
			  $orderBy = '',
			  $orderDirection = 'asc',
			  // $method = 'products',
			  // $method = 'product-categories',
			  $method = 'products/full-feed',
			  $httpRequestMethod = 'GET',
			  $apiUser,
			  $apiKey;

	function __construct() {
		$this->curl = new Curl();

		$this->curl->setOpt(CURLOPT_SSL_VERIFYHOST, 0);
		$this->curl->setOpt(CURLOPT_SSL_VERIFYPEER, 0);
		$this->curl->setConnectTimeout(60);
		$this->curl->setTimeout(60);
	}

	public function setLoginURL($url) {
		$this->loginURL = rtrim($url, '/');
	}

	public function setMainURL($url) {
		$this->mainURL = $url;
	}

	public function run($username, $password, $p3 = '', $p4 = '') {
		$this->apiUser = $username;
		$this->apiKey = $password;
		$this->method = ($p3) ? 'products' : $this->method;
		$this->queryString = '/'.$this->method.'/?order_by='.$this->orderBy.'&order_direction='.$this->orderDirection.'&records_per_page=200';
		$this->setHeaders();
		$this->curl->get($this->loginURL.$this->queryString);
		// die(print_r($this->curl->errorMessage, 1));

		return ($this->curl->error) ? false : (($p3) ? $this->curl->response->result : $this->curl->response);
	}

	private function setHeaders() {
		$this->curl->setHeaders([
			'Date' => gmdate('r'),
		    'X-NodWS-User' => $this->apiUser,
		    'X-NodWS-Auth' => $this->getSignatureString(rawurldecode($this->queryString)),
		    'X-NodWS-Accept' => $this->method
			]);
	}

	private function getSignatureString($queryString) {
        //HTTP verb , Query String , / , client, GMT date
        $signatureString = $this->httpRequestMethod . trim($queryString, '/') . '/' . $this->apiUser . gmdate('r');
        return $this->hmacSha1($signatureString);
    }

    private function hmacSha1($msg) {
        $blocksize = 64;
        $opad = str_repeat(chr(0x5c), $blocksize);
        $ipad = str_repeat(chr(0x36), $blocksize);
        $key = (strlen($this->apiKey) < $blocksize) ? ($this->apiKey . str_repeat(chr(0),
            ($blocksize - strlen($this->apiKey)))) : $this->apiKey;
        $hmac = sha1(($key ^ $opad) . sha1(($key ^ $ipad) . $msg, true), true);
        return base64_encode($hmac);
    }
}