<?php

class view {
	private $view;
	private $titleTag;
	private $css = array();
	private $publicCSS = array();
	private $js = array();
	private $publicJS = array();
	private $favicon;
	private $thisUser;

	function __construct() {
		$this->thisUser = userObj::init();
	}

	public function rander($name, array $vars = NULL, $layout = true) {
		if (!empty($vars)) {
			extract($vars);
		}

		if (($layout) &&
			((is_readable(config::get('path/appViews').$this->view.'/'.$name.'.php')) ||
			(is_readable(config::get('path/sysViews').$this->view.'/'.$name.'.php')))) {
			require_once config::get('path/appViews').'tamplate/styles/'.config::get('site/style').'/header_overall.php';
			require_once ((is_readable(config::get('path/appViews').$this->view.'/'.$name.'.php')) ?  config::get('path/appViews') : config::get('path/sysViews')).$this->view.'/'.$name.'.php';
			require_once config::get('path/appViews').'tamplate/styles/'.config::get('site/style').'/footer_overall.php';
		} else {
			require_once ((is_readable(config::get('path/appViews').$this->view.'/'.$name.'.php')) ? config::get('path/appViews') : config::get('path/sysViews')).$this->view.'/'.$name.'.php';
		}
	}

	public function setTitleTag($value) {
		$this->titleTag = $value;
	}

	public function getTitleTag() {
		return $this->titleTag;
	}

	public function setView($value) {
		$this->view = $value;
	}

	public function setCSS($value) {
		if (is_array($value)) {
			foreach ($value as $view => $css) {
				$this->css[$view] = $css;
			}
		} else {
			$this->css[] = $value;
		}
	}

	public function setPublicCSS($value) {
		if (is_array($value)) {
			foreach ($value as $css) {
				$this->publicCSS[] = $css;
			}
		} else {
			$this->publicCSS[] = $value;
		}
	}

	public function getCSS() {
		$return = '';

		if (!empty($this->publicCSS)) {
			foreach ($this->publicCSS as $css) {
				$return .= '<link type="text/css" rel="stylesheet" href="'.config::get('site/domain').'app/public/styles/'.config::get('site/style').'/'.'css/'.$css.'.css" />'."\n\r";
			}
		}

		if (!empty($this->css)) {
			foreach ($this->css as $view => $css) {
				$return .= '<link type="text/css" rel="stylesheet" href="'.config::get('site/domain').'app/views/'.
				((is_numeric($view)) ? $this->view : $view).'/styles/'.config::get('site/style').'/'.'css/'.$css.'.css" />'."\n\r";
			}
		}
		return $return;
	}

	public function setJS($value) {
		if (is_array($value)) {
			foreach ($value as $view => $js) {
				$this->js[$view] = $js;
			}
		} else {
			$this->js[] = $value;
		}
	}

	public function setPublicJS($value) {
		if (is_array($value)) {
			foreach ($value as $js) {
				$this->publicJS[] = $js;
			}
		} else {
			$this->publicJS[] = $value;
		}
	}

	public function getJS() {
		$return = '';

		if (!empty($this->publicJS)) {
			foreach ($this->publicJS as $js) {
				$return .= '<script type="text/javascript" src="'.config::get('site/domain').'app/public/js/'.$js.'.js" ></script>'."\n\r";
			}
		}

		if (!empty($this->js)) {
			foreach ($this->js as $view => $js) {
				$return .= '<script type="text/javascript" src="'.config::get('site/domain').'app/views/'.((is_numeric($view)) ? $this->view : $view).'/js/'.$js.'.js" ></script>'."\n\r";
			}
		}
		return $return;
	}

	public function setFavicon($value) {
		$this->favicon = $value;
	}

	public function getFavicon() {
		return (!empty($this->favicon)) ? '<link rel="shortcut icon" href="'.config::get('site/domain').'app/public/'.$this->favicon.'" />'."\n\r" : '';
	}
}