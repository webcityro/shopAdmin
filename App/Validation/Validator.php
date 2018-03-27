<?php
namespace Storemaker\App\Validation;

use Respect\Validation\Validator as Respect;
use Respect\Validation\Exceptions\NestedValidationException;

class Validator {
	private $container,
			  $errors = [];

	function __construct($container)	{
		$this->container = $container;
	}

	public function check($request, array $items) {
		foreach ($items as $field => $item) {
			try {
				$item['rules']->setName($item['label'])->assert($request->getParam($field));
			} catch (NestedValidationException $e) {
				$msg = $e->getMessages();
				$this->errors[$field] = isset($msg[0]['customMsg']) ? [$this->container->language->translate($msg[0]['customMsg'])] : $msg;
			}
		}

		if ($this->failed()) {
			$this->container->jsonResponse->setError($this->errors);
			$_SESSION['validationErrors'] = $this->errors;
		}

		return $this;
	}

	public function failed()	{
		return !empty($this->errors);
	}

	public function getErrors() {
		unset($_SESSION['validationErrors']);
		return $this->errors;
	}
}