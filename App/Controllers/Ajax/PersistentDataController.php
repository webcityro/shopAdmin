<?php
namespace Storemaker\App\Controllers\Ajax;
use Storemaker\System\Libraries\Controller;
use Storemaker\App\Models\Ajax\PersistentData;

class PersistentDataController extends Controller {

	public function save($request, $responce, $args) {
		$data = [
			'userID' => $this->auth->user()->id,
			'itemID' => $request->getParam('itemID'),
			'languageID' => $request->getParam('languageID'),
			'name' => $request->getParam('name'),
			'section' => $request->getParam('section'),
			'current' => '1',
			'data' => $request->getParam('data'),
		];

		if ($args['id'] == 0) {
			PersistentData::where('section', $request->getParam('section'))->update(['current' => '0']);
			$newData = PersistentData::create($data);
		} else {
			$newData = PersistentData::where('id', $args['id'])->update($data);
		}


		if ($newData) {
			$this->jsonResponse->setData('newData', $newData);
		} else {
			$this->jsonResponse->setError($this->language->translate('persistentDataUpdateError'));
			$this->setCurrent($request, $responce, $args, '1');
		}
	}

	public function setCurrent($request, $responce, $args, $current = false) {
		PersistentData::where('section', $request->getParam('section'))->update(['current' => '0']);

		if ($current == '1' || $request->getParam('section') == '1') {
			return PersistentData::where('id', $args['id'])->update(['current' => '1']);
		}
		return true;
	}

	public function delete($request, $responce, $args) {
		if (!PersistentData::where('id', $args['id'])->delete()) {
			$this->jsonResponse->setError($this->language->translate('persistentDataDeleteError'));
		}
	}
}
