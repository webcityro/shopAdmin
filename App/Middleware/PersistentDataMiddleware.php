<?php
namespace Storemaker\App\Middleware;
use Storemaker\App\Models\Ajax\PersistentData;

class PersistentDataMiddleware extends Middleware {
	function __invoke($request, $response, $next) {
		if ($this->container->auth->check() && !$request->isXhr()) {
			$newData = PersistentData::where('userID', $this->container->auth->user()->id)
											  ->where('itemID', '0')
											  ->where('section', $request->getUri()->getPath())->get();

			$editData = PersistentData::where('userID', $this->container->auth->user()->id)
											  ->where('itemID', '!=', '0')
											  ->where('section', $request->getUri()->getPath())->get();

			$currentItem = PersistentData::where('userID', $this->container->auth->user()->id)
											  ->where('current', '1')
											  ->where('section', $request->getUri()->getPath())->get();
			$this->container->view->getEnvironment()->addGlobal('persistentData', [
				'newData' => $newData,
				'editData' => $editData,
				'currentItem' => $currentItem
			]);
			$this->container->view->getEnvironment()->addGlobal('currentPath', $request->getUri()->getPath());
		}

		return $next($request, $response);
	}
}