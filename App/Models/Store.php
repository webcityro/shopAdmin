<?php
namespace Storemaker\App\Models;

use Illuminate\Database\Eloquent\Model;

class Store extends Model {
	protected $table = 'stores',
				 $container;

	function __construct($container)	{
		$this->container = $container;
	}

	public function setIntoConfig()	{
		$url = ((!empty($_SERVER['HTTPS']) && $_SERVER['HTTPS'] != 'off') ? 'https://' : 'http://') . str_replace('www.', '', $_SERVER['HTTP_HOST']) . rtrim(dirname($_SERVER['PHP_SELF']), '/.\\');
		$store = $this->where('url', $url)->first();

		if ($store) {
			$this->container->config->set('store/id', $store->id);
			$this->container->config->set('store/name', $store->name);
		} else {
			$this->container->config->set('store/id', '0');
			$this->container->config->set('store/name', 'default');
		}
	}
}