<?php
namespace Storemaker\App\Middleware;

class UserPermissionsMiddleware extends Middleware {
	private $section,
			  $action;

	function __construct($container, $section, $action)	{
		$this->container = $container;
		$this->section = $section;
		$this->action = $action;
	}

	function __invoke($request, $response, $next) {
		if (!$this->container->auth->isOwner() && !$this->container->auth->permissions($this->section, $this->action)) {
			if (!empty($request->getParam('ajaxRequestToken'))) {
				header('Content-Type: application/json;charset=utf-8');
				$this->container->jsonResponse->setData('access', false);
				$this->container->jsonResponse->getResponse();
			}
			$this->container->flash->addMessage('error', $this->container->language->translate('userAccessDenied'));
			return $response->withRedirect($this->container->router->pathFor('home'));
		}

		return $next($request, $response);
	}
}