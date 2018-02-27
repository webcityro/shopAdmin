<?php
namespace Storemaker\System\Libraries;

class Upload {
	private $field;
	private $originalName;
	private $newName;
	private $type;
	private $size;
	private $tmpName;
	private $ext;
	private $error;

	private $allowTypes = '*';
	private $sizeMultiplier = 1048576;
	private $maxSize;
	private $nameNoSpaces = false;
	private $nameEncrept = false;
	private $overwrite = false;
	private $uploadDir = '';
	private $uploadPath = '';
	private $errors = array();

	function __construct() {
		$this->maxSize = 2 * $this->sizeMultiplier;
	}

	public function setField($value) {
		$this->field = $_FILES[$value];
		$this->originalName = $this->field['name'];
		$this->type = $this->field['type'];
		$this->size = $this->field['size'];
		$this->tmpName = $this->field['tmp_name'];
		$this->error = $this->field['error'];
		$kbum = explode('.', $this->originalName);
		$this->ext = strtolower(end($kbum));
	}

	public function setAllowedFileType($value) {
		$this->allowTypes = $value;
	}

	public function setNameNoSpaces	($value) {
		$this->nameNoSpaces = $value;
	}

	public function setNameEncript($value) {
		$this->nameEncrept = $value;
	}

	public function setOverwrite($value) {
		$this->overwrite = $value;
	}

	public function setUploadDir($value) {
		$this->uploadDir = $value;
	}

	public function setMaxSize($value) {
		$this->maxSize = $this->sizeMultiplier * $value;
	}

	public function getUploadPath()	{
		return $this->uploadPath;
	}

	public function getNewFileName() {
		return $this->newName;
	}

	private function checkError() {
		if ($this->error == 0) {
			$return = true;
		} else {
			$errorCodes = array(1 => 'Fisierul <strong>'.$this->originalName.'</strong> depaseste marimea maxima de <strong>'.$this->maxSize.' MB</strong> permisa de noi!',
						 2 => 'Fisierul <strong>'.$this->originalName.'</strong> depaseste marimea maxima de <strong>'.$this->maxSize.' MB</strong> permisa de noi!',
						 3 => 'Fisierul <strong>'.$this->originalName.'</strong> nu a putut fi incarcat',
						 4 => 'Nu ai selectat nici un fisier!');

			$this->errors[] = $errorCodes[$this->error];
			$return = false;
		}
		return $return;
	}

	private function checkFileType() {
		$return = false;
		$type = explode('/', $this->type);

		if (is_array($this->allowTypes)) {
			if ((in_array($this->ext, $this->allowTypes)) || (in_array($type[0], $this->allowTypes))) {
				$return = true;
			} else {
				$this->errors[] = 'Fisierul <strong>>'.$this->originalName.'</strong> are extensia <strong>'.$this->ext.'</strong> iar noi acceptam doar unmtoarele tipuri de fisiere: <em>'.rtrim(implode(', ', $this->allowTypes), ', ').'</em>!';
			}
		} else if (($this->allowTypes == '*') || ($this->allowTypes == $type[0]) || ($this->allowTypes == $this->ext)) {
			$return = true;
		}
		return $return;
	}

	private function checkSize() {
		if ($this->size > $this->maxSize) {
			$this->errors[] = 'Fisierul <strong>'.$this->originalName.'</strong> depaseste marimea maxima de <strong>'.$this->maxSize.' MB</strong> permisa de noi!';
			$return = false;
		} else {
			$return = true;
		}
		return $return;
	}

	private function encriptName() {
		$this->newName =  md5(uniqid(rand(0, microtime()))).'.'.$this->ext;
	}

	private function removeNameSpaces() {
		$this->newName = preg_replace("/\s+/", '_', $this->originalName);
	}

	private function dirExists() {
		return (is_dir($this->uploadDir)) ? true : false;
	}

	private function fileExists() {
		return (is_readable($this->uploadDir.'/'.$this->newName)) ? true : false;
	}

	public function getErrors() {
		return $this->errors;
	}

	public function run() {
		$return = false;

		if (($this->checkError()) && ($this->checkFileType()) && ($this->checkSize())) {
			if ($this->nameNoSpaces) {
				$this->removeNameSpaces();
			} else if ($this->nameEncrept) {
				$this->encriptName();
			} else {
				$this->newName = $this->originalName;
			}

			if (!$this->dirExists()) {
				mkdir($this->uploadDir, 0777);
			}

			if ((!$this->overwrite) && ($this->fileExists())) {
				$i = 1;
				do {
					if ($this->nameEncrept) {
						$this->encriptName();
					} else {
						$this->newName = str_replace(array('.'.$this->ext, '('.($i - 1).').'.$this->ext), '', $this->newName).'('.$i.').'.$this->ext;
					}
					$i++;
				} while ($this->fileExists());
			}

			$this->uploadPath = $this->uploadDir.'/'.$this->newName;

			if (move_uploaded_file($this->tmpName, $this->uploadPath)) {
				$return = true;
			}
		}
		return $return;
	}
}