<?php
session_start();

use Storemaker\System\Libraries\Config;
use Storemaker\System\Libraries\Session;
use Storemaker\System\Libraries\Language;
use Storemaker\App\Models\Setting;
use Storemaker\App\Models\Store;
use Respect\Validation\Validator as V;
use PHPMailer\PHPMailer\PHPMailer;

require_once '../vendor/autoload.php';


$app = new Slim\App([
	'settings' => [
		'displayErrorDetails' => $config['system']['environment'] == 'development',
	]
]);

$container = $app->getContainer();
$capsule = new \Illuminate\Database\Capsule\Manager();

$container['config'] = function ($container) use($config) {
	return new Config($config);
};

$container['jsonResponse'] = function ($container) {
	return new \Storemaker\System\Libraries\JsonResponse();
};

$capsule->addConnection($container['config']->get('database'));
$capsule->setAsGlobal();
$capsule->bootEloquent();

$container['db'] = function ($container) use($capsule) {
	return $capsule;
};

$settings = new Setting($container);
$store = new Store($container);
$store->setIntoConfig();
$settings->setIntoConfig();

$container['language'] = function ($container) {
	return new Language($container);
};

$container['csrf'] = function ($container) {
	return new \Slim\Csrf\Guard();
};

$container['flash'] = function ($container) {
	return new \Slim\Flash\Messages();
};

$container['email'] = function ($container) {
	$email = new PHPMailer();

	$email->isSMTP();
	$email->SMTPAuth = $container->config->get('email/smtp/auth');
	$email->SMTPSecure = $container->config->get('email/smtp/securety');
	$email->SMTPDebug = $container->config->get('email/smtp/debug');

	$email->Host = $container->config->get('email/smtp/host');
	$email->Username = $container->config->get('email/smtp/username');
	$email->Password = $container->config->get('email/smtp/password');
	$email->Port = $container->config->get('email/smtp/port');

	$email->SMTPOptions = [
		'ssl' => [
			'verify_peer' => false,
			'verify_peer_name' => false,
			'allow_self_signed' => true
		]
	];

	$email->setFrom($container->config->get('email/noreply'), $container->config->get('site/name'));
	$email->addReplyTo($container->config->get('email/noreply'), $container->config->get('site/name'));
	$email->isHTML(true);
	return $email;
};

$container['auth'] = function ($container) use($app) {
	return new \Storemaker\App\Auth\Auth($container);
};

$container['view'] = function ($container) {
	$view = new \Slim\Views\Twig($container->config->get('path/appViews'), [
		'cache' => false,
		'debug' => true
	]);

	$view->addExtension(new \Slim\Views\TwigExtension($container->router, $container->request->getUri()));
	$view->addExtension(new \Twig_Extension_Debug());

	$auth = $container->auth;
	unset($auth->user()->password);
	$view->getEnvironment()->addGlobal('auth', $auth);

	$view->getEnvironment()->addGlobal('config', $container->config);
	// $view->getEnvironment()->addGlobal('language', $container->language);
	$view->getEnvironment()->addGlobal('flash', $container->flash);

	return $view;
};

$container['validator'] = function ($container) {
	return new \Storemaker\App\Validation\Validator($container);
};

V::with('Storemaker\\App\\Validation\\Rules\\');

$app->add($container->csrf);
$app->add(new \Storemaker\App\Middleware\LanguageMiddleware($container));
$app->add(new \Storemaker\App\Middleware\ValidationErrorsMiddleware($container));
$app->add(new \Storemaker\App\Middleware\CsrfViewMiddleware($container));
$app->add(new \Storemaker\App\Middleware\PaginationMiddleware($container));
$app->add(new \Storemaker\App\Middleware\PersistentDataMiddleware($container));

$container['HomeController'] = function ($container) {
	return new \Storemaker\App\Controllers\HomeController($container);
};

$container['AccountsController'] = function ($container) {
	return new \Storemaker\App\Controllers\Users\AccountsController($container);
};

$container['UsersGroupsController'] = function ($container) {
	return new \Storemaker\App\Controllers\Users\GroupsController($container);
};

$container['ForgetPasswordController'] = function ($container) {
	return new \Storemaker\App\Controllers\Users\ForgetPasswordController($container);
};

$container['unitsOfMeasurementController'] = function ($container) {
	return new \Storemaker\App\Controllers\System\UnitsOfMeasurementController($container);
};

$container['PersistentDataController'] = function ($container) {
	return new \Storemaker\App\Controllers\Ajax\PersistentDataController($container);
};

$app->get('/router.js', function($req, $res, $args) {
	$routerJs = new \Llvdl\Slim\RouterJs($this->router);
	return $routerJs->getRouterJavascriptResponse();
});

require_once '../App/Routes.php';