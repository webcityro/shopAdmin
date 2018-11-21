<?php

class bootstrap {
	function __construct() {
	}

	public function dispatch() {
		$controler = url::getSegment(1);

		if (class_exists($controler)) {
			$c = new $controler;
			$c->loadModel($controler);

			if (url::getSegment(2)) {
				if ($c->getArgsToIndex()) {
					$c->index($this->fetchArgs(2));
				} else {
					$action = url::getSegment(2);

					if (method_exists($c, $action)) {
						if (url::getSegment(3)) {
							$c->$action($this->fetchArgs(3));
						} else {
							$c->$action();
						}
					} else {
						$msg = 'Nu exista metoda <strong>'.$action.'</strong> in controler-ul <strong>'.$controler.'</strong>';
						errors::systemError($msg);
						return false;
					}
				}
			} else {
				$c->index();
			}
		} else {
			$msg = 'Nu exista clasa corespunzatoare in controler-ul <strong>'.$controler.'</strong>';
			errors::systemError($msg);
			return false;
		}
	}

	public function fetchArgs($start) {
		$args = '';
		$segNum = url::getNumSegments();

		if ($segNum > $start) {
			for ($x=$start; $x <= $segNum; $x++) {
				$args[] = url::getSegment($x);
			}
		} else {
			$args = url::getSegment($start);
		}

		return $args;
	}
}

?>