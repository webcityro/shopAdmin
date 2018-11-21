<?php
namespace Storemaker\App\Middleware;

class LanguageMiddleware extends Middleware {
	function __invoke($request, $response, $next) {
		$this->container->language->load('global');
		$this->container->language->load('Validate');
		$this->container->view->getEnvironment()->addGlobal('language', $this->container->language);
		$this->container->view->getEnvironment()->addGlobal('formLanguagesHeader', $this->container->view->fetch('_layout_tamplate/styles/'.$this->container->config->get('site/style').'/partials/formLanguagesHeader.twig', ['list' => $this->container->language->getList()]));

		return $next($request, $response);
	}
}