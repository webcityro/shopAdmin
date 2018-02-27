<?php
namespace Storemaker\System\Libraries;

use Storemaker\App\Libraries\User;

class View {
	private $view;
	private $titleTag;
	private $css = array();
	private $publicCSS = array();
	private $js = array();
	private $publicJS = array();
	private $favicon;
	private $thisUser;

	function __construct() {
		$this->thisUser = User::init();
	}

	public function rander($name, array $vars = NULL, $layout = true) {
		if (!empty($vars)) {
			extract($vars);
		}

		if (($layout) &&
			((is_readable(Config::get('path/appViews').$this->view.'/'.$name.'.php')) ||
			(is_readable(Config::get('path/sysViews').$this->view.'/'.$name.'.php')))) {
			require_once Config::get('path/appViews').'_layout_tamplate/styles/'.Config::get('site/style').'/header_overall.php';
			require_once ((is_readable(Config::get('path/appViews').$this->view.'/'.$name.'.php')) ?  Config::get('path/appViews') : Config::get('path/sysViews')).$this->view.'/'.$name.'.php';
			require_once Config::get('path/appViews').'_layout_tamplate/styles/'.Config::get('site/style').'/footer_overall.php';
		} else {
			require_once ((is_readable(Config::get('path/appViews').$this->view.'/'.$name.'.php')) ? Config::get('path/appViews') : Config::get('path/sysViews')).$this->view.'/'.$name.'.php';
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
				$return .= '<link type="text/css" rel="stylesheet" href="'.Config::get('url/style').'/'.'css/'.$css.'.css" />'."\n\r";
			}
		}

		if (!empty($this->css)) {
			foreach ($this->css as $view => $css) {
				$return .= '<link type="text/css" rel="stylesheet" href="'.Config::get('site/domain').'App/Views/'.
				((is_numeric($view)) ? $this->view : $view).'/styles/'.Config::get('site/style').'/'.'css/'.$css.'.css" />'."\n\r";
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
				$return .= '<script src="'.Config::get('url/style').'js/'.$js.'.js" ></script>'."\n\r";
			}
		}

		if (!empty($this->js)) {
			foreach ($this->js as $view => $js) {
				$return .= '<script src="'.Config::get('site/domain').'App/Views/'.((is_numeric($view)) ? $this->view : $view).'/js/'.$js.'.js" ></script>'."\n\r";
			}
		}
		return $return;
	}

	public function setFavicon($value) {
		$this->favicon = $value;
	}

	public function getFavicon() {
		return (!empty($this->favicon)) ? '<link rel="shortcut icon" href="'.Config::get('url/style').$this->favicon.'" />'."\n\r" : '';
	}
}