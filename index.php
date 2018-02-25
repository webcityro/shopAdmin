<?php

//ob_start();
require_once '../config.php';

$config = array(
		'site' => [
			'name'   => 'Proline',
			'style'  => 'default',
			'domain' => 'http://localhost/proline/new_admin/'
		]);
$config['url']['temp']			 = $config['site']['domain'].'app/temp/';
$config['path']['base'] 		 = dirname(realpath(__FILE__)).'/';
$config['path']['sys'] 			 = $config['path']['base'].'system/';
$config['path']['app'] 			 = $config['path']['base'].'app/';
$config['path']['sysControlers'] = $config['path']['sys'].'controlers/';

$config['path']['appControlers']      = $config['path']['app'].'controlers/';
$config['path']['appContracts']       = $config['path']['app'].'contracts/';
$config['path']['sysModels'] 	      = $config['path']['sys'].'models/';
$config['path']['appModels'] 	      = $config['path']['app'].'models/';
$config['path']['sysLibs'] 		      = $config['path']['sys'].'libs/';
$config['path']['appLibs'] 		      = $config['path']['app'].'libs/';
$config['path']['sysViews'] 	      = $config['path']['sys'].'views/';
$config['path']['appViews']	          = $config['path']['app'].'views/';
$config['path']['fileStorage']	      = $config['path']['base'].'file_storage/';
$config['path']['users']	          = $config['path']['fileStorage'].'users/';
$config['path']['functions']	 	  = $config['path']['app'].'functions/';
$config['path']['temp']	 	  		  = $config['path']['app'].'temp/';
$config['database'] = [
			'driver'   => 'mysql',
			'host'	   => 'localhost',
			'user'	   => 'root',
			'password' => '',
			'name'	   => 'proline',
			'prefix'	   => 'wc_'
		];
$config['icon'] = [
			'error' => $config['site']['style'].'app/public/styles/'.$config['site']['style'].'/images/error_icon.png',
			'ok' => $config['site']['style'].'app/public/styles/'.$config['site']['style'].'/images/ok_icon.png',
			'loader' => $config['site']['style'].'app/public/styles/'.$config['site']['style'].'/images/loader_icon.png'
		];
$config['ownerID'] = 1;

require_once $config['path']['sys'].'core.php';