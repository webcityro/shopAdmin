<?php
namespace Storemaker\App\Controllers;
use Storemaker\System\Libraries\Controller;

class HomeController extends Controller {

	function construct() {

	}

	public function index($request, $responce) {
		return $this->view->render($responce, 'home/index.twig');
	}
}
