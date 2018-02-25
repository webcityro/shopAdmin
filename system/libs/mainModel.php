<?php
class mainModel {
	private $core;
	protected $db;
	protected $pagination;
	protected $image;
	protected $upload;
	protected $thisUser;

	function __construct() {
		// $this->core 	= core::init();
		$this->thisUser = userObj::init();
		$this->db 	    = database::init();
	}
}