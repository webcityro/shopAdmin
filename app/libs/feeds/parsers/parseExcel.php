<?php
namespace feeds\parsers;
use PhpOffice\PhpSpreadsheet\IOFactory;
require_once \config::get('path/appContracts').'feeds/parser.php';

class parseExcel extends parser implements \feedParser {
	public $type = 'Excel';
	private $contents,
			  $structure,
			  $imagesDir,
			  $fileType = 'Xlsx',
			  $imagesArr = [];

	function __construct($contents, $structure) {
		if ($contents == 'getName') {
			return;
		}
		$this->structure = $structure;
	}

	public function run($structure = false, $contents = false) {
		if (!$this->contentFile) {
			return false;
		}
		// print_r($this->structure);
		// die();
		$this->imagesDir = microtime();
		mkdir(\config::get('path/temp').$this->imagesDir, 0777);

		$reader = IOFactory::createReader($this->fileType);
		$worksheet = $reader->load($this->contentFile);
		$worksheetNames = $worksheet->getSheetNames();

		foreach ($this->structure as $elementKey => $element) {
			$path = $this->parsePath($element->path);

			if ($path['path'] = 'all') {
				foreach ($worksheetNames as $sheetName) {
					$this->parseSheet($worksheet->getSheetByName($sheetName), $element, $path);
				}
			} else if (in_array($path['path'], $worksheetNames)) {
				$this->parseSheet($worksheet->getSheetByName($path['path'], $element, $path));
			}
		}
	}

	private function parseSheet($sheet, $element, $path) {
		$sheetArray = $sheet->toArray(null, true, true, true);

		$this->setImagesArray($sheet);

		if (isset($element->this) && !empty((array) $element->this)) {
			if (!$this->findItems($element->this, $sheet, $path['path'])) {

			}
		}

		if (isset($element->items) && !empty((array) $element->items)) {
			foreach ($element->items as $key => $item) {
				$rowID = new \stdClass();

				if ($key == 'all') {
					foreach ($sheetArray as $rowIndex => $row) {
						$rowID->row = $row;

						if (!$this->findItems($item, $rowID, 'row')) {
							continue;
						}

						$this->children($sheet, $row, $element, $rowIndex);

						if ($this->stop) {
							break;
						}
					}
					return true;
				}

				if (!isset($sheetArray[$key])) {
					$this->setError(['rowNotFound' => $key]);
					continue;
				}

				$rowID->row = $sheetArray[$key];

				if (!$this->findItems($item, $rowID, 'row')) {
					continue;
				}

				$this->children($sheet, $sheetArray[$key], $element, $key);

				if ($this->stop) {
					break;
				}
			}
		}

		return ($path['path'] == 'all') ? true : $this->children($sheet, $sheetArray[$path['path']], $element);
	}

	private function children($sheet, $rowArray, $element, $rowIndex) {
		if (isset($element->children) && !empty((array) $element->children)) {
			foreach ($element->children as $elementKey => $elementChild) {
				$path = $this->parsePath($elementChild->path);

				if (!isset($elementChild->items) || empty((array)$elementChild->items)) {
					continue;
				}
				$this->row($sheet, $rowArray, $elementChild->items, $rowIndex);

				// if ($this->stop) {
				// 	break;
				// }
			}
			return true;
		}
		return false;
	}

	private function row($sheet, $rowArray, $items, $rowIndex) {
		foreach ($items as $key => $item) {
			$cellID = $key.$rowIndex;

			if (!isset($rowArray[$key])) {
				$this->setError(['collumnNotFound' => $key]);
				continue;
			}

			$cell = new \stdClass();

			$cellLink = $sheet->getCell($cellID)->getHyperlink()->getURL();
			$cellImage = (!empty($this->imagesArr[$cellID])) ? $this->imagesArr[$cellID] : '';
			$cellValue = $sheet->getCell($cellID)->getValue();
			$cell->{$cellID} = (!empty($cellLink)) ? $cellLink : ((!empty($cellImage)) ? $cellImage : $cellValue);

			if (!$this->findItems($item, $cell, $cellID)) {
				continue;
			}
		}
	}

	private function setImagesArray($sheet) {
		foreach ($sheet->getDrawingCollection() as $drawing) {
			if ($drawing instanceof \PhpOffice\PhpSpreadsheet\Worksheet\MemoryDrawing) {
				ob_start();
				call_user_func($drawing->getRenderingFunction(), $drawing->getImageResource());
				$imageContents = ob_get_contents();
				ob_end_clean();
				$extension = $this->getImageExt($drawing->getMimeType());
			} else {
				$imageContents = '';
				$zipReader = fopen($drawing->getPath(),'r');

				while (!feof($zipReader)) {
					$imageContents .= fread($zipReader,1024);
				}

				fclose($zipReader);
				$extension = $drawing->getExtension();
			}

			$cell = $drawing->getCoordinates();
			$imgName = $cell.'_'.microtime().'.'.$extension;
			$imgPath = \config::get('path/temp').$this->imagesDir.'/'.$imgName;
			$imgURL = \config::get('site/domain').'app/temp/'.$this->imagesDir.'/'.$imgName;

			if (\file::make($imgPath, $imageContents)) {
				$this->imagesArr[$cell] = $imgURL;
			}
		}
	}

	private function getImageExt($type)	{
		switch ($type) {
			case \PhpOffice\PhpSpreadsheet\Worksheet\MemoryDrawing::MIMETYPE_PNG :
			$extension = 'png';
			break;
			case \PhpOffice\PhpSpreadsheet\Worksheet\MemoryDrawing::MIMETYPE_GIF:
			$extension = 'gif';
			break;
			case \PhpOffice\PhpSpreadsheet\Worksheet\MemoryDrawing::MIMETYPE_JPEG :
			$extension = 'jpg';
			break;
		}
		return $extension;
	}
}