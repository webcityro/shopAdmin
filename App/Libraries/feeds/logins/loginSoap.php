<?php
namespace Storemaker\App\Libraries\Feeds\Logins;
use Storemaker\App\Contracts\Feeds;

class LoginSoap implements FeedLogin {
	public $type = 'Soap';
	protected $loginURL,
			  $mainURL,
			  $client,
			  $login,
			  $results,
			  $resultsClass,
			  $loginMethod,
			  $resultsMethod,
			  $clientArgs = [];

	public function setLoginURL($url) {
		$this->loginURL = $url;
	}

	public function setMainURL($url) {
		$this->mainURL = $url;
	}

	public function setClientArgs($args) {
		eval('$this->clientArgs = '.$args.';');
	}

	public function setloginMethod($method) {
		$this->loginMethod = $method;
	}

	public function setResultsMethod($method) {
		$this->resultsMethod = $method;
	}

	public function setDefineVars($vars) {
		eval($vars);
	}

	function run($username = '', $password = '', $p3 = '', $p4 = '') {
		try {
			$this->client = new \SoapClient($this->loginURL, $this->clientArgs);


			if (!empty($this->loginMethod)) {
				eval('$this->login = $this->client->'.$this->loginMethod.';');
			}
			if (!empty($this->resultsMethod)) {
				$this->resultsMethod = ((substr($this->resultsMethod, 0, 7) == '$this->') ?
												($this->resultsMethod) :
												((!empty($this->login)) ? '$this->login' : '$this->client').'->'.$this->resultsMethod);
				eval('$this->results = '.$this->resultsMethod.';');
			}

		} catch (\SOAPFault $e) {
			echo $e->getMessage();
			return false;
		}
		return (empty($this->results)) ? $this->login : $this->results;
	}
}