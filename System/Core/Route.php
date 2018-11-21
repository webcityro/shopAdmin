<?php
namespace Storemaker\System\Core;

class Route {
	private $method,
			  $pattern,
			  $callable,
			  $groups = [],
			  $identifier,
			  $arguments = [],
			  $name,
			  $basePath;

	public function __construct($method, $pattern, $callable, $groups = [], $identifier = 0) {
		$this->method  = $method;
      $this->pattern  = $pattern;
      $this->callable = $callable;
      $this->groups   = $groups;
      $this->identifier = 'route' . $identifier;

      $this->parsePattren();
	}

	public function setName($name) {
		$this->name = $name;
	}

	public function getMethod() {
		return $this->method;
	}

	public function getName() {
		return $this->name;
	}

	public function getBasePath() {
		return $this->basePath;
	}

	public function getArgumentCount() {
		return count($this->arguments);
	}

	public function getIdentifier() {
		return $this->identifier;
	}

/*
/users/profile{/userName/param2}
*/
	private function parsePattren() {
		if (strpos($this->pattern, '{')) {
			$patternArr = explode('{', $this->pattern);

			$this->arguments = explode(rtrim($patternArr[1], '}'), '/');
			$this->basePath = $patternArr[0];
		} else {
			$this->basePath = $this->pattern;
		}

	}

	public function run($url) {
		$params = [];
		$currentURL = $url->getURL();

		if ($this->getArgumentCount() > 0) {
			$urlSegments = $url->getSegments();

			if ($this->getArgumentCount() <= count($urlSegments)) {
				return false;
			}

			foreach ($this->arguments as $argument) {
				array_unshift($params, array_pop($urlSegments));
			}

			$currentURL = implode('/', $urlSegments);
			$currentURL = empty($currentURL) ? '/' : $currentURL;
		}

		$currentURL = substr($currentURL, 0, 1) == '/' ? $currentURL : '/'.$currentURL;

		if ($currentURL != $this->basePath) {
			return false;
		}

		$callableArr = explode(':', $this->callable);
		$class = $callableArr[0];
		$method = $callableArr[1];

		if (!class_exists($class)) {
			return false;
		}

		if (!method_exists($class, $method)) {
			return false;
		}

		$core = new Core($currentURL == '/' ? 'Home' : $currentURL);

		$controller = new $class;

		$controller->setModel(str_replace(['Storemaker\\System\\Controllers', 'Storemaker\\App\\Controllers'], ['Storemaker\\System\\Models', 'Storemaker\\App\\Models'], $class));
		call_user_func_array([$controller, $method], $params);
		return true;
	}
}