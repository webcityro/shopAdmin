<?php

class groups extends mainControler {
	private $validate,
			  $siteSections = [],
			  $permissions = [];

	function __construct() {
		parent::__construct();
		$this->loginOnly();
		language::load('userGroups');
		$this->validate = new validate;
		$this->view->setView('users');
		$this->view->setCSS(['groups']);
		$this->view->setJS('groups');
		$this->view->setTitleTag(language::translate('pageTitle'));

		$this->siteSections = [
	  		'users'							=> language::translate('users'),
	  		'users/groups' 				=> language::translate('users/groups'),
	  		'users/profile'				=> language::translate('users/profile'),
	  		'categories' 					=> language::translate('categories'),
	  		'attributes' 					=> language::translate('attributes'),
	  		'attributes/groups'			=> language::translate('attributes/groups'),
	  		'attributes/templates'		=> language::translate('attributes/templates'),
	  		'products'						=> language::translate('products'),
	  		'products/addedPrice'		=> language::translate('products/addedPrice'),
	  		'suppliers'						=> language::translate('suppliers'),
	  		'suppliers/feeds'				=> language::translate('suppliers/feeds'),
	  		'suppliers/feeds/products' => language::translate('suppliers/feeds/products'),
	  		'aliases'						=> language::translate('aliases'),
	  		'errors' 						=> language::translate('errors'),
	  ];
	  $this->permissions = [
	  		'view'	=> language::translate('view'),
	  		'add' 	=> language::translate('add'),
	  		'edit'	=> language::translate('edit'),
	  		'delete' => language::translate('delete'),
	  ];
	}

	public function index2() {
		$groups = $this->model->getAll();
		$this->view->rander('groups', [
			'siteSections' => $this->siteSections,
			'permissions' => $this->permissions,
			'groupRows' => $groups->results(),
			'pagination' => $groups->getPagination()
		]);
	}

	public function get($id) {
		$row = $this->model->get($id)[0];

		if ($row) {
			$this->jsonResponse->setData('row', $row);
		} else {
			$this->jsonResponse->setError(language::translate('resultsNotFond', language::translate('theGroup')));
		}
		$this->jsonResponse->getResponse();
	}

	public function addOrUpdate($id) {
		if (!token::check('addGroup', input::post('addOrUpdateToken'))) {
			die(language::translate('ilegalPostTentative'));
		}

		$this->jsonResponse->setData('newToken', token::generate('addGroup'));

		if (!$this->validateGroupName(input::post('name'))) {
			$this->jsonResponse->setError($this->validate->getErrors());
		} else if ($this->model->checkExists(input::post('name'), $id)) {
			$this->jsonResponse->setError(language::translate('errorGroupNameExists', input::post('name')));
		} else if ($this->validatePermitions(input::post('permissions'))) {
			if ($id == 0) {
				$id = $this->model->add(input::post('name'), input::post('permissions'));

				if ($id === false) {
					$this->jsonResponse->setError(language::translate('canNotAdd'));
				}
			} else {
				if (!$this->model->update(input::post('name'), input::post('permissions'), $id)) {
					$this->jsonResponse->setError(language::translate('canNotUpdate'));
				}
			}
			$this->jsonResponse->setData('id', $id);
		}

		$this->jsonResponse->getResponse();
	}

	private function validateGroupName($name)	{
		$this->validate->check(['groupName' => $name], ['groupName' => [
			'label' => language::translate('formLabelFName'),
			'required' => 'true',
			'max' => 25]
		]);
		return $this->validate->passed();
	}

	private function validatePermitions($permissions) {
		$passed = true;

		foreach (json_decode($permissions) as $key => $permission) {
			if (!$permission->view && in_array(true, (array)$permission)) {
				$this->jsonResponse->setError(language::translate('errorPermissionWithoutViewingRight', $this->siteSections[$key]));
				$passed = false;
			}
		}

		return $passed;
	}

	public function delete($id)	{
		if (!token::check('delete', input::post('deleteToken'))) {
			die(language::translate('ilegalPostTentative'));
		}

		$this->jsonResponse->setData('newToken', token::generate('delete'));

		if (!$this->model->delete($id)) {
			$this->jsonResponse->setError(language::translate('canNotDelete'));
		}
		$this->jsonResponse->getResponse();
	}
}