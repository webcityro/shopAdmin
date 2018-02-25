<?php

require_once $config['path']['sysLibs'].'config.php';

config::setInitial($config);

require_once config::get('path/functions').'general.php';

$composerLibs = config::get('path/appLibs').'vendor/autoload.php';

if (is_readable($composerLibs)) {
	require_once $composerLibs;
}

spl_autoload_register(function ($class) {
	if (is_readable(config::get('path/sysControlers').$class.'.php')) {
		require_once config::get('path/sysControlers').$class.'.php';
	} else if (is_readable(config::get('path/sysModels').$class.'.php')) {
		require_once config::get('path/sysModels').$class.'.php';
	} else if (is_readable(config::get('path/sysLibs').$class.'.php')) {
		require_once config::get('path/sysLibs').$class.'.php';
	} else {
		if ((url::getSegment(2)) && (is_dir(config::get('path/appControlers').url::getSegment(1))) &&
			(is_readable(config::get('path/appControlers').url::getSegment(1).'/'.$class.'.php'))) {
			require_once config::get('path/appControlers').url::getSegment(1).'/'.$class.'.php';
		} else if (is_readable(config::get('path/appControlers').$class.'.php')) {
			require_once config::get('path/appControlers').$class.'.php';
		} else if ((url::getSegment(2)) && (is_dir(config::get('path/appModels').url::getSegment(1))) &&
			(is_readable(config::get('path/appModels').url::getSegment(1).'/'.$class.'.php'))) {
			require_once config::get('path/appModels').url::getSegment(1).'/'.$class.'.php';
		} else if (is_readable(config::get('path/appModels').$class.'.php')) {
			require_once config::get('path/appModels').$class.'.php';
		} else if (is_readable(config::get('path/appLibs').$class.'.php')) {
			require_once config::get('path/appLibs').$class.'.php';
		}
	}
});

class core {
	private $bootstrap;
	private $controler;
	private static $instance = false;
	protected $db;

	private function __construct() {
		session::init();
		$settings = new settingsModel();
		$store = new storesModel();

		url::init();
		$this->controler = url::getSegment(1);
		$store->setIntoConfig();
		$settings->setIntoConfig();
		language::init();
		language::load($this->controler);
		categoriesObj::init();
		oc::init();
 	}

	public static function init() {
		if (!self::$instance) {
			self::$instance = new core();
		}
		return self::$instance;
	}

	public function runBootstrap() {
		$this->bootstrap = new bootstrap();
		$this->bootstrap->dispatch();
	}
}

$core = core::init();
$core->runBootstrap();