<?php
namespace Storemaker\App\Middleware;

class ValidationErrorsMiddleware extends Middleware {
	function __invoke($request, $response, $next) {
		if (isset($_SESSION['validationErrors'])) {
			$this->container->view->getEnvironment()->addGlobal('validationErrors', $_SESSION['validationErrors']);
			unset($_SESSION['validationErrors']);
		}

		return $next($request, $response);
	}
}