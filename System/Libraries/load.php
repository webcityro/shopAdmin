<?php

class load {
	public static function sysHelpper($name) {
		$path = config::get('path/sys').'helppers/'.$name.'.php';

		if (is_readable($path)) {
			require_once $path;
		} else {
			$msg = 'Nu sa gsit helpper-ul <strong>'.$name.'</strong> in <pre>'.$path.'</pre>';
			errors::error404($msg);
		}
	}

	public static function appHelpper($name) {
		$path = config::get('path/app').'helppers/'.$name.'.php';

		if (is_readable($path)) {
			require_once $path;
		} else {
			$msg = 'Nu sa gsit helpper-ul <strong>'.$name.'</strong> in <pre>'.$path.'</pre>';
			errors::error404($msg);
		}
	}
}