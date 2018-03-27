<?php
namespace Storemaker\System\Libraries;

class JsonResponse {
	private $status = true,
			  $errors = [],
			  $tokens = [],
			  $data = [];

	public function setStatus($status) {
		$this->status = $status;
	}

	public function setData($key, $data) {
		$this->data[$key] = $data;
	}

	public function setToken($key, $token) {
		$this->tokens[$key] = $token;
	}

	public function setError($value, $key = false) {
		$this->status = false;

		if (is_array($value)) {
			foreach ($value as $key => $v) {
				$this->errors[$key] = $v;
			}
			return;
		}
		$this->errors[$key ?: count($this->errors)]  = $value;
	}

	public function getData() {
		return [
			'status' => $this->status,
			'errors' => $this->errors,
			'data' => $this->data,
			'token' => $this->tokens
		];
	}

	public function getResponse($die = true) {
		$data = json_encode($this->getData());
		if ($die) {
			die($data);
		}
		return $data;
	}
}