<?php
namespace Storemaker\App\Controllers;
use Storemaker\System\Libraries;

class Categories extends Controller {
	private $catName,
			$catDesc,
			$catMetaTitle,
			$catMetaDesc,
			$catMetaKeywords,
			$catStatus,
			$catTop,
			$catTopColumn,
			$catWidth,
			$catLenght,
			$catLengthClassID,
			$catHeight,
			$catWeight,
			$catWeightClassID,
			$sort,
			$parentID,
			$imageLink,
			$imageThumb,
			$newImage = '';

	function __construct() {
		parent::__construct();
		$this->loginOnly();
		$this->view->setTitleTag('Panou de admin / Categorii');
		// $this->view->setCSS(['categories']);
		$this->view->setPublicJS(['viewCategoryes', 'imageViewer', 'google_image_search']);
		$this->view->setPublicCSS(['imageViewer', 'google_image_search']);
		$this->view->setJS('categories');
		$this->view->setView('categories');
	}

	public function index() {
		$this->view->rander('index');
	}

	private function setPostVars() {
		$this->catName 			= input::post('catName');
		$this->catDesc 			= input::post('catDesc');
		$this->catMetaTitle 	= input::post('catMetaTitle');
		$this->catMetaDesc  	= input::post('catMetaDesc');
		$this->catMetaKeywords  = input::post('catMetaKeywords');
		$this->catStatus 		= (int)input::post('catStatus');
		$this->catTop 			= (int)input::post('catTop');
		$this->catWidth 		= input::post('catWidth');
		$this->catTopColumn 	= (input::post('catTopColumn') != '') ? (int)input::post('catTopColumn') : 1;
		$this->catLenght 		= input::post('catLenght');
		$this->catLengthClassID = (int)input::post('catLengthClassID');
		$this->catHeight 		= input::post('catHeight');
		$this->catWeight 		= input::post('catWeight');
		$this->catWeightClassID = (int)input::post('catWeightClassID');
		$this->sort 			= (int)input::post('sort');
		$this->parentID 		= (int)input::post('catParentID');
		$this->imageLink 		= input::post('imageLink');
		$this->imageThumb 		= input::post('imageThumb');
	}

	public function add() {
		$this->setPostVars();
		$jsonReturn = ['status' => 'ok', 'msg' => ''];

		if (!empty($this->catName) && !empty($this->catMetaTitle)) {
			if (!empty($this->imageLink) && !empty($this->imageThumb)) {
				$this->addImage();
			}
			if ($id = $this->model->add($this->newImage, $this->catName, $this->catDesc, $this->catMetaTitle, $this->catMetaDesc, $this->catMetaKeywords, $this->catStatus, $this->catTop, $this->catTopColumn, $this->sort, $this->parentID)) {
				$jsonReturn['image'] = $this->newImage;
				$jsonReturn['id'] = $id;

				if ($this->checkPresetsPosted() && !$this->model->insertPresets($this->catLenght, $this->catWidth, $this->catHeight, $this->catWeight, $this->catLengthClassID, $this->catWeightClassID, $id)) {
					$jsonReturn['status'] = 'error';
					$jsonReturn['msg'] = 'Presetarile categoriei nu sau putut adauga!';
				}
			} else {
				$jsonReturn['status'] = 'error';
				$jsonReturn['msg'] = 'Categoria nu s-a putut adauga!';
			}
		} else {
			$jsonReturn['status'] = 'error';
			$jsonReturn['msg'] = 'Campurile marcate cu (*) sunt obigatorii!';
		}
		die(json_encode($jsonReturn));
	}

	private function addImage() {
		$di = new downloadImage();
		$newImage = $di->downloadImage($this->imageLink);
		$thumbName = str_replace(oc::getProductsImageDIR(), '', $newImage);
		$newThumb = $di->downloadThumb($this->imageThumb, $thumbName);
		$di->makeThumb($newThumb);
		$this->newImage = $newImage;
	}

	private function makeThumb($thumb)	{
		$imgObj = new image();
		$imgObj->setTarget(oc::getImagesDIR().$thumb);
		$imgObj->setNewCopy(oc::getImagesDIR().$thumb);
		return $imgObj->thumb(100, 100);
	}

	public function getForEdit($id)	{
		$jsonReturn = ['status' => 'ok'];

		if ($row = $this->model->get($id)) {
			$jsonReturn['row'] = $row;
		} else {
			$jsonReturn['status'] = 'error';
		}
		die(json_encode($jsonReturn));
	}

	public function edit($id) {
		$this->setPostVars();
		$jsonReturn = ['status' => 'ok', 'msg' => ''];

		if (!empty($this->catName) && !empty($this->catMetaTitle)) {
			$catImage = $this->model->getImage($id);

			if (!empty($this->imageLink) && !empty($this->imageThumb)) {
				if ($catImage != $this->imageLink) {
					if (!empty($catImage)) {
						$this->model->deleteImage($id);
					}
					$this->addImage();
				} else {
					$this->newImage = $catImage;
				}
			} else if (!empty($catImage)) {
				$this->model->deleteImage($id);
			}

			if ($id = $this->model->update($this->newImage, $this->catName, $this->catDesc, $this->catMetaTitle, $this->catMetaDesc, $this->catMetaKeywords, $this->catStatus, $this->catTop, $this->catTopColumn, $id)) {
				$jsonReturn['id'] = $id;
				$jsonReturn['image'] = $this->newImage;

				if ($this->checkPresetsPosted()) {
					$action = ($this->model->checkPresetsExists($id)) ? 'updatePresets' : 'insertPresets';

					if (!$this->model->$action($this->catLenght, $this->catWidth, $this->catHeight, $this->catWeight, $this->catLengthClassID, $this->catWeightClassID, $id)) {
						$jsonReturn['status'] = 'error';
						$jsonReturn['msg'] = 'Presetarile categoriei nu sau putut edita!';
					}
				}

			} else {
				$jsonReturn['status'] = 'error';
				$jsonReturn['msg'] = 'Categoria nu s-a putut edita!';
			}
		} else {
			$jsonReturn['status'] = 'error';
			$jsonReturn['msg'] = 'Campurile marcate cu (*) sunt obigatorii!';
		}
		die(json_encode($jsonReturn));
	}

	public function delete($id, $what)	{
		$jsonReturn = ['status' => 'ok', 'msg' => ''];
		$jsonReturn['parentID'] = $this->model->delete($id, $what);

		if ($jsonReturn['parentID'] === false) {
			$jsonReturn = ['status' => 'error', 'msg' => 'Nu s-a putut sterge categoria acum!'];
		}
		die(json_encode($jsonReturn));
	}

	public function moveCat($moveID, $moveToID)	{
		if ($this->model->changeParent($moveID, $moveToID)) {
			die(json_encode(['status' => 'ok']));
		} else {
			die(json_encode(['status' => 'error', 'msg' => 'Nu s-a putut muta categoria acum!']));
		}
	}

	public function sort($oldSort, $newSort, $id, $parentID) {
		$jsonReturn = ['status' => 'ok', 'msg' => ''];

		if (!$this->model->sort($oldSort, $newSort, $id, $parentID)) {
			$jsonReturn['status'] = 'error';
			$jsonReturn['msg'] = 'Nu s-a putut schimba ordinea categoriei!';
		}
		die(json_encode($jsonReturn));
	}

	private function checkPresetsPosted() {
		return (!empty($this->catLenght) || !empty($this->catWidth) || !empty($this->catHeight) || !empty($this->catWeight) || !empty($this->catLengthClassID) || !empty($this->catWeightClassID)) ? true : false;
	}
}