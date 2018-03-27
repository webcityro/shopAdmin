<?php
namespace Storemaker\System\Libraries;

class Controller {
	protected $model,
				 $thisUser,
				 $container,
				 $paginationPath;

	function __construct($container) {
		$this->container = $container;

		if (method_exists($this, 'construct')) {
			$this->construct();
		}
	}

	function __get($property) {
		if (isset($this->container->{$property})) {
			return $this->container->{$property};
		} else if (isset($this->{$property})) {
			return $this->{$property};
		}
	}

	protected function setPaginationPath($path) {
		$this->paginationPath = $path;
	}

	protected function paginationToJSON($paginator, $range = 10)	{
		$paginator->setPath($this->paginationPath);
		$this->container->jsonResponse->setData('pagination', $this->container->view->fetch('_layout_tamplate/styles/'.$this->container->config->get('site/style').'/partials/pagination.twig', ['paginator' => $paginator, 'range' => $range]));
	}

	public function __destruct() {
		if (!empty($this->container->request->getParam('ajaxRequestToken'))) {
			unset($_SESSION['validationErrors']);
			header('Content-Type: application/json;charset=utf-8');
			$this->container->jsonResponse->setToken($this->container->request->getParam('ajaxRequestToken'),  [
				'nameKey' => $this->container->csrf->getTokenNameKey(),
				'name' => $this->container->csrf->getTokenName(),
				'valueKey' => $this->container->csrf->getTokenValueKey(),
				'value' => $this->container->csrf->getTokenValue()
			]);
			$this->container->jsonResponse->getResponse();
		}
	}
}