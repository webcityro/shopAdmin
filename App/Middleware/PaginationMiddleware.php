<?php
namespace Storemaker\App\Middleware;

class PaginationMiddleware extends Middleware {
	function __invoke($request, $response, $next) {
		$container = $this->container;
		$paginate = new \Twig\TwigFunction('paginate', function ($pagination, $range = 10) use($container, $request, $response) {
			$pagination->setPath($request->getUri()->getPath());
			return $container->view->fetch('_layout_tamplate/styles/'.$container->config->get('site/style').'/partials/pagination.twig', ['paginator' => $pagination, 'range' => $range]);
		});

		$this->container->view->getEnvironment()->addFunction($paginate);

		return $next($request, $response);
	}
}