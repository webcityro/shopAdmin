<?php

error_reporting(E_ALL);
//ob_start();

$config = array(
		'site' => [
			'name'   => 'Proline',
			'style'  => 'default',
			'domain' => 'http://localhost/proline/new_admin/Public/'
		]);
$config['url']['temp']			 = $config['site']['domain'].'file_storage/temp/';
$config['url']['style']			 = $config['site']['domain'].'styles/'.$config['site']['style'].'/';
$config['path']['base'] 		 = dirname(realpath(__FILE__)).'/../';
$config['path']['sys'] 			 = $config['path']['base'].'System/';
$config['path']['app'] 			 = $config['path']['base'].'App/';
$config['path']['sysControlers'] = $config['path']['sys'].'Controlers/';

$config['path']['appControlers']      = $config['path']['app'].'Controlers/';
$config['path']['appContracts']       = $config['path']['app'].'Contracts/';
$config['path']['sysModels'] 	      = $config['path']['sys'].'Models/';
$config['path']['appModels'] 	      = $config['path']['app'].'Models/';
$config['path']['sysLibs'] 		      = $config['path']['sys'].'Libtaries/';
$config['path']['appLibs'] 		      = $config['path']['app'].'Libraries/';
$config['path']['sysViews'] 	      = $config['path']['sys'].'Views/';
$config['path']['appViews']	          = $config['path']['app'].'Views/';
$config['path']['fileStorage']	      = $config['path']['base'].'Public/file_storage/';
$config['path']['users']	          = $config['path']['fileStorage'].'users/';
$config['path']['temp']	 	  		  = $config['path']['app'].'Temp/';
$config['database'] = [
			'driver'   => 'mysql',
			'host'	   => 'localhost',
			'user'	   => 'root',
			'password' => '',
			'name'	   => 'proline',
			'prefix'	   => 'wc_'
		];
$config['icon'] = [
			'error' => $config['url']['style'].'images/error_icon.png',
			'ok' => $config['url']['style'].'images/ok_icon.png',
			'loader' => $config['url']['style'].'images/loader_icon.png'
		];
$config['ownerID'] = 1;

require_once '../vendor/autoload.php';

$app = new Storemaker\System\Core\Router();

require_once '../App/Routes.php';


Storemaker\System\Libraries\Config::setInitial($config);

$app->boot();
