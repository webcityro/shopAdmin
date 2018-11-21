<?php
namespace Storemaker\System\Core;

use Storemaker\System\Libraries\Url as Url;

class Router {
	private $routes = [],
			  $routesCount = 0,
			  $basePath,
			  $url;

	public function __construct() {
		$this->url = new Url();
	}

	/**
		$route = url
		$cm = controller:method
		'/hello[/{name}]'
	*/
	public function get($patten, $handler)	{
		return $this->map('GET', $patten, $handler);
	}

	public function post($patten, $handler)	{
		return $this->map('POST', $patten, $handler);
	}

	public function put($patten, $handler)	{
		return $this->map('PUT', $patten, $handler);
	}

	public function patch($patten, $handler)	{
		return $this->map('PATCH', $patten, $handler);
	}

	public function delete($patten, $handler)	{
		return $this->map('DELETE', $patten, $handler);
	}

	public function options($patten, $handler)	{
		return $this->map('OPTIONS', $patten, $handler);
	}

	private function map($method, $patten, $handler) {
		$route = new Route($method, $patten, $handler, [], $this->routesCount);

		$this->routes[$route->getIdentifier()] = $route;
		$this->routesCount++;
		return $route;
	}

	public function setBasePath($path) {
		$this->basePath = $path;
	}

	public function boot() {
		// die('boot');
		// echo $this->url->getURL();
		$method = $_SERVER['REQUEST_METHOD'];

		foreach ($this->routes as $route) {
			if ($route->getMethod() != $method) {
				continue;
			}

			if ($route->run($this->url)) {
				break;
			}
		}
	}
}