<?php
namespace Storemaker\App\Middleware;

class CsrfViewMiddleware extends Middleware {
	function __invoke($request, $response, $next) {
		$container = $this->container;
		$csrf = new \Twig\TwigFunction('csrf', function ($id) use($container, $request, $response) {
			$id = preg_replace("/\./", '-', $id);
			$token = $this->makeToken();

			$container->jsonResponse->setToken($id, $token);

			return '<input type="hidden" id="tokenName'.ucfirst($id).'" name="'.$token['nameKey'].'" value="'.$token['name'].'">
			<input type="hidden" id="tokenValue'.ucfirst($id).'" name="'.$token['valueKey'].'" value="'.$token['value'].'">';
		});

		$this->container->view->getEnvironment()->addFunction($csrf);

		return $next($request, $response);
	}

	private function makeToken() {
		return [
			'nameKey' => $this->container->csrf->getTokenNameKey(),
			'name' => $this->container->csrf->getTokenName(),
			'valueKey' => $this->container->csrf->getTokenValueKey(),
			'value' => $this->container->csrf->getTokenValue()
		];
	}
}