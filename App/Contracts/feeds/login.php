<?php
namespace Storemaker\App\Contracts\Feeds;

interface Login {
	public function setLoginURL($url);
	public function setMainURL($url);
	public function run($username, $password, $p3, $p4);
}