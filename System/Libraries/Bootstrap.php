<?php
namespace Storemaker\System\Libraries;

class Bootstrap {
	private $controlerName;
	private $controler;
	private $action;
	private $actionIndex = 2;
	private $arguments;
	private $argumentsIndex = 3;

	function __construct() {
	}

	public function dispatch() {
		$this->setControler();
		$this->controler->loadModel($this->controlerName);
		$this->setArguments();
		$this->setAction();

		// echo "<pre>";
		// print_r($this->controler);
		// print_r($this->action);
		// print_r($this->arguments);
		// die();
		call_user_func_array([$this->controler, $this->action], $this->arguments);
	}

	private function setControler()	{
		$this->controlerName = url::getSegment(2);

		if (!class_exists($this->controlerName)) {
			$this->controlerName = url::getSegment(1);
			if (!class_exists($this->controlerName)) {
				$msg = 'Nu exista clasa corespunzatoare in controler-ul <strong>'.$this->controlerName.'</strong>';
				errors::systemError($msg);
				die();
			}
		} else {
			$this->actionIndex = 3;
			$this->argumentsIndex = 4;
		}
		$this->controler = new $this->controlerName;
	}

	private function setAction() {
		if (url::getSegment($this->actionIndex)) {
			$this->action = ($this->controler->getArgsToIndex()) ? 'index' : url::getSegment($this->actionIndex);

			if (!method_exists($this->controler, $this->action)) {
				$msg = 'Nu exista metoda <strong>'.$this->action.'</strong> in controler-ul <strong>'.$this->controlerName.'</strong>';
				errors::systemError($msg);
				die();
			}
		} else {
			$this->action = ($this->actionIndex == 2) ? 'index' : 'index2';
		}
	}

	private function setArguments()	{
		$this->argumentsIndex = ($this->controler->getArgsToIndex()) ? $this->argumentsIndex - 1 : $this->argumentsIndex;
		$this->arguments = $this->fetchArgs();
	}

	private function fetchArgs() {
		$args = [];
		$segNum = url::getNumSegments();

		if ($segNum >= $this->argumentsIndex) {
			for ($x=$this->argumentsIndex; $x <= $segNum; $x++) {
				$args[] = url::getSegment($x);
			}
		}
		return $args;
	}
}

?>