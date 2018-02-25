<?php

interface feedLogin {
	public function setLoginURL($url);
	public function setMainURL($url);
	public function run($username, $password, $p3, $p4);
}