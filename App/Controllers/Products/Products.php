<?php
namespace Storemaker\App\Controllers\Products;
use Storemaker\System\Libraries;

class Products extends Controller {
	private $newGroupID = [],
			$newGroupSort = 0,
			$newAttributeSort = 0,
			$downloadImage;

	function __construct() {
		parent::__construct();
		$this->loginOnly();
		$this->view->setTitleTag('Panou de admin / Produse');
		$this->view->setCSS(['sabloane' => 'sabloane', 'produse']);
		$this->view->setPublicJS('viewCategoryes');
		$this->view->setPublicJS(['google_image_search', 'imageViewer']);
		$this->view->setPublicCSS(['google_image_search', 'imageViewer']);
		$this->view->setJS([/*'sabloane' => 'sabloane',*/ 'produse']);
		$this->view->setView('produse');
		$this->downloadImage = new downloadImage();
	}

	public function index() {
		// $this->model->getSablonsCount(4);
		$this->view->rander('index', ['productsTree' => $this->model->getProductsTree()]);
	}

	public function getSablonsByCategory($catID) {
		$cats = $this->model->getSablonModel()->getSablonsByCategory($catID);
		$jsonReturn = ['status' => ($cats) ? 'ok' : 'error', 'rows' => $cats];
		die(json_encode($jsonReturn));
	}

	public function getSablonContents($sablonID) {
		$jsonReturn = ['status' => 'error', 'msg' => 'Nu s-a gasit sablonu!'];
		$sablon = $this->model->getSablonModel()->getSablonGroups($sablonID);

		if ($sablon) {
			$jsonReturn['rows'] = $sablon;
			$jsonReturn['status'] = 'ok';
		}
		die(json_encode($jsonReturn));
	}

	public function getCategoryPresetes($catID)	{
		$jsonReturn = ['status' => 'error', 'msg' => ''];
		$sablons = $this->model->getSablonModel()->getSablonsByCategory($catID);
		$presetes = $this->model->getCategoryPresetes($catID);

		if ($sablons) {
			$jsonReturn['sablons'] = $sablons;
			$jsonReturn['status'] = 'ok';
		}
		if ($presetes) {
			$jsonReturn['presetes'] = $presetes;
			$jsonReturn['status'] = 'ok';
		}
		die(json_encode($jsonReturn));
	}

	public function getForEdit($id) {
		$sablonID = $this->model->getSablonModel()->getProductSablonID($id);

		$product = [
			'sablonID' => $sablonID,
			'data' => $this->model->getData($id),
			'attributes' => $this->model->getAttributes($id, $sablonID),
			'images' => $this->model->getImages($id),
			'imagesThumbDIR' => oc::getConfig('url').'image/cache/',
			'imagesDIR' => oc::getConfig('url').'image/'
		];
		die(json_encode($product));
	}

	public function add() {
		$jsonReturn = ['status' => 'ok'];
		$catID = (int)input::post('catID');
		$sablonID = (int)input::post('sablonID');
		$data = json_decode(input::post('data'));
		$attributes = json_decode(input::post('attributes'));
		$images = json_decode(input::post('images'));

		$id = $this->model->addData($data);

		if ($id !== false) {
			$this->model->addToCaregory($id, $catID);
			$this->model->getSablonModel()->addProduct($id, $sablonID);
			$this->model->addToStore($id, oc::getStoreID());
			$this->addAttributes($id, $attributes);
			$this->addImages($id, $images);

			session::flash('home', ['tyoe' => 'success', 'msg' => 'Produsul a fost adaugat cu succes!']);
		} else {
			$jsonReturn['status'] = 'error';
			$jsonReturn['msg'] = 'Produsul nu sa putut adauga!';
		}
		die(json_encode($jsonReturn));
	}

	public function update($id) {
		$jsonReturn = ['status' => 'ok', 'msg' => []];
		$catID = input::post('catID');
		$sablonID = input::post('sablonID');
		$updates = json_decode(input::post('updates'));

		if (!$this->updateData($id, $updates->data)) {
			$jsonReturn['msg'][] = 'Datele produsului nu s-au putut aptualiza tn totalitate!';
		}
		if (!$this->updateGroups($id, $updates->groups)) {
			$jsonReturn['msg'][] = 'Grupurile produsului nu s-au putut aptualiza tn totalitate!';
		}
		if (!$this->updateAttributes($id, $updates->attributes)) {
			$jsonReturn['msg'][] = 'Atributele produsului nu s-au putut aptualiza tn totalitate!';
		}
		if (!$this->updateImages($id, $updates->images)) {
			$jsonReturn['msg'][] = 'Imaginile produsului nu s-au putut aptualiza tn totalitate!';
		}

		if (!empty($catID)) {
			if (!$this->model->updateCaregory($id, $catID)) {
				$jsonReturn['msg'][] = 'Categoria produsului nu s-a putut aptualiza!';
			}
		}
		if (!empty($sablonID)) {
			if (!$this->model->getSablonModel()->updateProduct($id, $sablonID)) {
				$jsonReturn['msg'][] = 'Sablonul produsului nu s-a putut aptualiza!';
			}
		}

		if (empty($jsonReturn['msg'])) {
			$jsonReturn['status'] = 'ok';
			session::flash('home', ['tyoe' => 'success', 'msg' => 'Produsul a fost aptualizat cu succes!']);
		} else {
			$jsonReturn['status'] = 'error';
		}

		die(json_encode($jsonReturn));
	}

	private function updateData($id, $data)	{
		if (count(get_object_vars($data))) {
			return $this->model->updateData($id, $data);
		}
		return true;
	}

	private function updateGroups($id, $groups)	{
		$groupsRun = true;

		if (count(get_object_vars($groups))) {
			foreach ($groups as $groupID => $updates) {
				// die($groupID.'   '.print_r($updates, 1));
				if (property_exists($updates, 'add')) {
					$oldGroupID = (empty($updates->add->oldID)) ? $groupID : $updates->add->oldID;
					if (substr($groupID, 0, 4) == 'new_') {
						$this->newGroupID[$oldGroupID] = $this->model->getSablonModel()->insertGroupInToOCTables($updates->add->name, $updates->add->sort);
						if (!$this->newGroupID[$oldGroupID]) {
							$groupsRun = false;
						}
					}
				}
				if (property_exists($updates, 'update')) {

					$oldGroupID = (empty($updates->update->oldID)) ? $groupID : $updates->update->oldID;
					if (!$newGroupID = $this->model->getSablonModel()->checkGroupNameExists($updates->update->name)) {
						$newGroupID = $this->model->getSablonModel()->insertGroupInToOCTables($updates->update->name, $updates->update->sort);
					}
					$this->newGroupID[$oldGroupID] = $newGroupID;
					if (!$this->model->changeAttributesGroup($id, $oldGroupID, $newGroupID)) {
						$groupsRun = false;
					}
				}
				if (property_exists($updates, 'delete')) {
					if (substr($groupID, 0, 4) != 'new_') {
						if (!$this->model->deleteAttributesByGroup($id, $groupID)) {
							$groupsRun = false;
						}
					}
				}
			}
		}
		return $groupsRun;
	}

	private function updateAttributes($id, $attributes)	{
		$attributesRun = true;

		if (count(get_object_vars($attributes))) {
			// die(print_r($attributes, 1));
			foreach ($attributes as $attributeID => $updates) {
				if (property_exists($updates, 'add')) {
					// die(print_r($updates, 1));
					$groupID = (!empty($this->newGroupID[$updates->add->groupID])) ? $this->newGroupID[$updates->add->groupID] : $updates->add->groupID;
					// echo $groupID;
					$newAttributeID = $this->addOrGetAttributeFromOCTable($id, $attributeID, $groupID, $updates->add->name, $updates->add->sort);
					if (property_exists($updates->add, 'oldID')) {
						$updateArr = (property_exists($updates->add, 'value')) ? ['attribute_id' => $newAttributeID, 'text' => $updates->add->value.' '.$updates->add->um] : ['attribute_id' => $newAttributeID];
						if (!$this->model->updateAttribute($id, $updates->add->oldID, $updateArr)) {
							$attributesRun = false;
						}
					} else {
						// echo(print_r($updates->add, 1));
						if (!$this->model->addAttribute($id, $newAttributeID, $updates->add->value.((empty($updates->add->um)) ? '' : ' '.$updates->add->um))) {
							$attributesRun = false;
						}
					}
				}
				if (property_exists($updates, 'update')) {
					$value = $updates->update->name.((empty($updates->update->um)) ? '' : ' '.$updates->update->um);

					if (!$this->model->updateAttribute($id, $attributeID, ['text' => $value])) {
						$attributesRun = false;
					}
				}
				if (property_exists($updates, 'delete')) {
					if (!$this->model->deleteAttribute($id, $attributeID)) {
						$attributesRun = false;
					}
				}
			}
		}
		return $attributesRun;
	}

	private function updateImages($id, $images)	{
		$imagesRun = true;
		// die(print_r($images, 1));
		if (count(get_object_vars($images))) {
			$productImageSetteed = false;

			foreach ($images as $imageID => $image) {
				if (property_exists($image, 'add')) {
					// die(print_r($image->add, 1));
					$newImage = $this->downloadImage->downloadImage($image->add->link);
					$thumbName = str_replace(oc::getProductsImageDIR(), '', $newImage);
					$newThumb = $this->downloadImage->downloadThumb($image->add->src, $thumbName);

					if (!$this->model->addImage($id, $newImage)) {
						$imagesRun = false;
					}
					$productImage = (property_exists($image, 'setProduseImage')) ? $image->setProduseImage : ((property_exists($image->add, 'producImage')) ? $image->add->productImage : false);

					if ($productImage == '1' || $productImage == 'true' || $productImage == true) {
						$imagesRun = $this->downloadImage->makeThumb($newThumb);
						if (!$this->model->setProductImage($id, $newImage)) {
							$imagesRun = false;
						} else {
							$productImageSetteed = true;
						}
					}
				} else if (property_exists($image, 'setProduseImage')) {
					if (($image->setProduseImage == '1' || $image->setProduseImage == 'true' || $image->setProduseImage == true) && !$productImageSetteed) {
						$imageRow = $this->model->getImage($id, $imageID);
						$this->downloadImage->makeThumb($imageRow->image);

						if (!$this->model->setProductImage($id, $imageRow->image)) {
							$imagesRun = false;
							$productImageSetteed = false;
						}
					}
				} else if (property_exists($image, 'delete')) {
					if (!$this->model->deleteImage($id, $imageID)) {
						$imagesRun = false;
					} else if (($image->delete->productImage == true || $image->delete->productImage == 'true' || $image->delete->productImage == 1) && !$productImageSetteed) {
						if (!$this->model->setProductImage($id, '')) {
							$imagesRun = false;
						}
					}
				}
			}
		}
		return $imagesRun;
	}

	private function addAttributes($id, $attributes) {
		foreach ($attributes as $groupID => $group) {
			if (substr($groupID, 0, 4) == 'new_') {
				$newGroupID = $this->model->getSablonModel()->insertGroupInToOCTables($group->name, $this->newGroupSort);
				$this->newGroupSort++;
			} else {
				$newGroupID = $groupID;
			}

			foreach ($group->attributes as $attributeID => $attribute) {
				// die(print_r($attribute, 0));
				$newAttributeID = $this->addOrGetAttributeFromOCTable($id, $attributeID, $newGroupID, $attribute->name, $attribute->sort);
				$this->model->addAttribute($id, $newAttributeID, $attribute->value);
			}
		}
	}

	private function addOrGetAttributeFromOCTable($id, $attributeID, $groupID, $name, $sort) {
		if (substr($attributeID, 0, 4) == 'new_') {
			$newAttributeID = $this->model->getSablonModel()->insertAttributeInOCTables($name, $groupID, $sort);
			$this->newAttributeSort++;
		} else {
			$newAttributeID = $attributeID;
		}
		return $newAttributeID;
	}

	private function addImages($id, $images) {
		// die(print_r($images, 1));
		foreach ($images as $imageID => $image) {
			if (substr($imageID, 0, 4) == 'new_') {
				$newImage = $this->downloadImage->downloadImage($image->url);
				$thumbName = str_replace(oc::getProductsImageDIR(), '', $newImage);
				$newThumb = $this->downloadImage->downloadThumb($image->thumb, $thumbName);
				$this->model->addImage($id, $newImage);

				if (property_exists($image, 'productImage') && $image->productImage == '1') {
					$this->downloadImage->makeThumb($newThumb);
					$this->model->setProductImage($id, $newImage);
				}
			}
		}
	}

	private function makeThumb($thumb)	{
		$imgObj = new image();
		$imgObj->setTarget(oc::getImagesDIR().$thumb);
		$imgObj->setNewCopy(oc::getImagesDIR().$thumb);
		return $imgObj->thumb(100, 100);
	}

	public function checkGroupExists($name)	{
		$group = $this->model->getGroupByName($name);

		if ($group) {
			$jsonReturn = ['status' => 'true', 'id' => $group->id, 'sort' => $group->sort];
		} else {
			$jsonReturn = ['status' => 'false'];
		}
		die(json_encode($jsonReturn));
	}

	public function checkAttributeExists($name)	{
		$attribute = $this->model->getAttributeByName($name);

		if ($attribute) {
			$jsonReturn = ['status' => 'true', 'id' => $attribute->id, 'groupID' => $attribute->groupID, 'sort' => $attribute->sort];
		} else {
			$jsonReturn = ['status' => 'false'];
		}
		die(json_encode($jsonReturn));
	}

	public function delete($id)	{
		$jsonReturn = ['status' => 'ok', 'msg' => ''];

		if (!$this->model->delete($id)) {
			$jsonReturn = ['status' => 'error', 'msg' => 'Nu s-a putut sterge produsul acum!'];
		}
		die(json_encode($jsonReturn));
	}
}