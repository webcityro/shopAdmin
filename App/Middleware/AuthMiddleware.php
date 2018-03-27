<?php
namespace Storemaker\App\Middleware;

class AuthMiddleware extends Middleware {
	function __invoke($request, $response, $next) {
		if (!$this->container->auth->check()) {
			$this->container->flash->addMessage('warning', $this->container->language->translate('loginRequired'));
			return $response->withRedirect($this->container->router->pathFor('users.accounts.login'));
		} else if ($this->container->auth->user()->active == '0') {
			$this->container->auth->logout();
			$this->container->flash->addMessage('warning', $this->container->language->translate('userDizctivated'));
			return $response->withRedirect($this->container->router->pathFor('users.accounts.login'));
		}

		return $next($request, $response);
	}
}