<?php
namespace Storemaker\App\Middleware;

class GuestMiddleware extends Middleware {
	function __invoke($request, $response, $next) {
		if ($this->container->auth->check()) {
			$this->container->flash->addMessage('warning', $this->container->language->translate('logoutRequired'));
			return $response->withRedirect($this->container->router->pathFor('home'));
		}

		return $next($request, $response);
	}
}