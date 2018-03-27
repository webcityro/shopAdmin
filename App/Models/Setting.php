<?php
namespace Storemaker\App\Models;
use Illuminate\Database\Eloquent\Model;

class Setting extends Model {
	protected $table = 'settings',
				 $container;

	function __construct($container)	{
		$this->container = $container;
	}
	public function setIntoConfig()	{
		$settings = $this->where('storeID', $this->container->config->get('store/id'))->get();

		if ($settings) {
			foreach ($settings as $row) {
				$this->container->config->set($row->code.'/'.$row->key, ($row->serialized == '1') ? json_decode($row->value) : $row->value);
			}
		}
	}
}