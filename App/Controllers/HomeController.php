<?php
namespace Storemaker\App\Controllers;
use Storemaker\System\Libraries\Controller;

class HomeController extends Controller {

	function construct() {
		// $this->view->setView('index');
	}

	public function index($request, $responce) {
		$js = '<script>

			var config = {
				domain: "'.$this->config->get('site/domain').'",
				url: '.json_encode($this->config->get("url")).',
				store: '.json_encode($this->config->get("store")).'
			};
		</script>';
		return $this->view->render($responce, 'home/index.twig', ['js' => $js]);
	}
}
