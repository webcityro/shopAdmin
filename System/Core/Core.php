<?php
namespace Storemaker\System\Core;

use Storemaker\System\Libraries\Session;
use Storemaker\System\Libraries\Language;
use Storemaker\App\Libraries\Categories;
use Storemaker\App\Models\Settings;
use Storemaker\App\Models\Stores;

class Core {
	private $currentURL;

	function __construct($currentURL) {
		$this->currentURL = $currentURL;

		Session::init();
		$settings = new Settings();
		$store = new Stores();

		$store->setIntoConfig();
		$settings->setIntoConfig();
		language::init();
		language::load($this->currentURL);
		// Categories::init();
 	}
}