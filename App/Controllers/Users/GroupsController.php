<?php
namespace Storemaker\App\Controllers\Users;
use Storemaker\System\Libraries\Controller;
use Respect\Validation\Validator as v;
use Storemaker\App\Models\Users\Group;

class GroupsController extends Controller {
	private $siteSections = [];

	function construct() {
		$this->language->load('UserGroups');

		$this->siteSections = [
	  		'users/accounts' => [
	  			'label' => $this->language->translate('users/accounts'),
	  			'actions' => [
			  		'view'	=> ['label' => $this->language->translate('view'), 'value' => false],
			  		'add' 	=> ['label' => $this->language->translate('add'), 'value' => false],
			  		'edit'	=> ['label' => $this->language->translate('edit'), 'value' => false],
			  		'delete' => ['label' => $this->language->translate('delete'), 'value' => false]
			  ]
	  		],
	  		'users/groups' => [
	  			'label' => $this->language->translate('users/groups'),
	  			'actions' => [
			  		'view'	=> ['label' => $this->language->translate('view'), 'value' => false],
			  		'add' 	=> ['label' => $this->language->translate('add'), 'value' => false],
			  		'edit'	=> ['label' => $this->language->translate('edit'), 'value' => false],
			  		'delete' => ['label' => $this->language->translate('delete'), 'value' => false]
			  ]
	  		],
	  		'users/profile' => [
	  			'label' => $this->language->translate('users/profile'),
	  			'actions' => [
			  		'view'	=> ['label' => $this->language->translate('view'), 'value' => false],
			  		'add' 	=> ['label' => $this->language->translate('add'), 'value' => false],
			  		'edit'	=> ['label' => $this->language->translate('edit'), 'value' => false],
			  		'delete' => ['label' => $this->language->translate('delete'), 'value' => false]
			  ]
	  		],
	  		'categories' => [
	  			'label' => $this->language->translate('categories'),
	  			'actions' => [
			  		'view'	=> ['label' => $this->language->translate('view'), 'value' => false],
			  		'add' 	=> ['label' => $this->language->translate('add'), 'value' => false],
			  		'edit'	=> ['label' => $this->language->translate('edit'), 'value' => false],
			  		'delete' => ['label' => $this->language->translate('delete'), 'value' => false]
			  ]
	  		],
	  		'attributes' => [
	  			'label' => $this->language->translate('attributes'),
	  			'actions' => [
			  		'view'	=> ['label' => $this->language->translate('view'), 'value' => false],
			  		'add' 	=> ['label' => $this->language->translate('add'), 'value' => false],
			  		'edit'	=> ['label' => $this->language->translate('edit'), 'value' => false],
			  		'delete' => ['label' => $this->language->translate('delete'), 'value' => false]
			  ]
	  		],
	  		'attributes/groups' => [
	  			'label' => $this->language->translate('attributes/groups'),
	  			'actions' => [
			  		'view'	=> ['label' => $this->language->translate('view'), 'value' => false],
			  		'add' 	=> ['label' => $this->language->translate('add'), 'value' => false],
			  		'edit'	=> ['label' => $this->language->translate('edit'), 'value' => false],
			  		'delete' => ['label' => $this->language->translate('delete'), 'value' => false]
			  ]
	  		],
	  		'attributes/templates' => [
	  			'label' => $this->language->translate('attributes/templates'),
	  			'actions' => [
			  		'view'	=> ['label' => $this->language->translate('view'), 'value' => false],
			  		'add' 	=> ['label' => $this->language->translate('add'), 'value' => false],
			  		'edit'	=> ['label' => $this->language->translate('edit'), 'value' => false],
			  		'delete' => ['label' => $this->language->translate('delete'), 'value' => false]
			  ]
	  		],
	  		'products' => [
	  			'label' => $this->language->translate('products'),
	  			'actions' => [
			  		'view'	=> ['label' => $this->language->translate('view'), 'value' => false],
			  		'add' 	=> ['label' => $this->language->translate('add'), 'value' => false],
			  		'edit'	=> ['label' => $this->language->translate('edit'), 'value' => false],
			  		'delete' => ['label' => $this->language->translate('delete'), 'value' => false]
			  ]
	  		],
	  		'products/addedPrice' => [
	  			'label' => $this->language->translate('products/addedPrice'),
	  			'actions' => [
			  		'view'	=> ['label' => $this->language->translate('view'), 'value' => false],
			  		'add' 	=> ['label' => $this->language->translate('add'), 'value' => false],
			  		'edit'	=> ['label' => $this->language->translate('edit'), 'value' => false],
			  		'delete' => ['label' => $this->language->translate('delete'), 'value' => false]
			  ]
	  		],
	  		'suppliers' => [
	  			'label' => $this->language->translate('suppliers'),
	  			'actions' => [
			  		'view'	=> ['label' => $this->language->translate('view'), 'value' => false],
			  		'add' 	=> ['label' => $this->language->translate('add'), 'value' => false],
			  		'edit'	=> ['label' => $this->language->translate('edit'), 'value' => false],
			  		'delete' => ['label' => $this->language->translate('delete'), 'value' => false]
			  ]
	  		],
	  		'suppliers/feeds' => [
	  			'label' => $this->language->translate('suppliers/feeds'),
	  			'actions' => [
			  		'view'	=> ['label' => $this->language->translate('view'), 'value' => false],
			  		'add' 	=> ['label' => $this->language->translate('add'), 'value' => false],
			  		'edit'	=> ['label' => $this->language->translate('edit'), 'value' => false],
			  		'delete' => ['label' => $this->language->translate('delete'), 'value' => false]
			  ]
	  		],
	  		'suppliers/feeds/products' => [
	  			'label' => $this->language->translate('suppliers/feeds/products'),
	  			'actions' => [
			  		'view'	=> ['label' => $this->language->translate('view'), 'value' => false],
			  		'add' 	=> ['label' => $this->language->translate('add'), 'value' => false],
			  		'edit'	=> ['label' => $this->language->translate('edit'), 'value' => false],
			  		'delete' => ['label' => $this->language->translate('delete'), 'value' => false]
			  ]
	  		],
	  		'system/unitsOfMeasurement' => [
	  			'label' => $this->language->translate('system/unitsOfMeasurement'),
	  			'actions' => [
			  		'view'	=> ['label' => $this->language->translate('view'), 'value' => false],
			  		'add' 	=> ['label' => $this->language->translate('add'), 'value' => false],
			  		'edit'	=> ['label' => $this->language->translate('edit'), 'value' => false],
			  		'delete' => ['label' => $this->language->translate('delete'), 'value' => false]
			  ]
	  		],
	  		'system/aliases' => [
	  			'label' => $this->language->translate('system/aliases'),
	  			'actions' => [
			  		'view'	=> ['label' => $this->language->translate('view'), 'value' => false],
			  		'add' 	=> ['label' => $this->language->translate('add'), 'value' => false],
			  		'edit'	=> ['label' => $this->language->translate('edit'), 'value' => false],
			  		'delete' => ['label' => $this->language->translate('delete'), 'value' => false]
			  ]
	  		],
	  		'system/errors' => [
	  			'label' => $this->language->translate('system/errors'),
	  			'actions' => [
			  		'view'	=> ['label' => $this->language->translate('view'), 'value' => false],
			  		'add' 	=> ['label' => $this->language->translate('add'), 'value' => false],
			  		'edit'	=> ['label' => $this->language->translate('edit'), 'value' => false],
			  		'delete' => ['label' => $this->language->translate('delete'), 'value' => false]
			  ]
	  		],
	  ];

	  $this->setPaginationPath('/users/groups');
	}

	public function index($request, $response) {
		return $this->view->render($response, 'users/groups.twig', [
			'siteSections' => $this->siteSections,
			'groupsRows' => $this->getAll($request)
		]);
	}

	public function get($request, $response, $id) {
		$group = Group::where('id', $id)->first();

		if (!$this->auth->isOwner() && $group->level < $this->auth->group()->level) {
			$this->jsonResponse->setData('access', false);
		} else {
			$this->jsonResponse->setData('group', $group);
		}

		return $response->withJson($this->jsonResponse->getData());
	}

	private function getAll($request) {
		return Group::where('storeID', $this->config->get('store/id'))->orderBy('level', 'ASC')->paginate(10, ['*'], 'page', $request->getParam('page'));
	}

	public function add($request, $response) {
		if ($this->checkAuthLevel(false, $request->getParam('groupLevel')) && !$this->validateFields($request) && $this->validatePermitions($request->getParam('permissions'))) {
			$newGroup = Group::create([
				'storeID' => $this->config->get('store/id'),
				'name' => $request->getParam('groupName'),
				'level' => $request->getParam('groupLevel'),
				'permissions' => $request->getParam('permissions')
			]);

			if ($newGroup) {
				$this->jsonResponse->setData('newGroup', $newGroup);
				$this->paginationToJSON($this->getAll($request));
			} else {
				$this->jsonResponse->setError($this->language->translate('cantAddGroup'));
			}
		}
	}

	public function update($request, $response, $id) {
		if (($group = $this->checkAuthLevel($id['id'])) && !$this->validateFields($request, $id['id']) && $this->validatePermitions($request->getParam('permissions'), $id['id'])) {
			$updatedGroup = $group->update([
				'name' => $request->getParam('groupName'),
				'level' => $request->getParam('groupLevel'),
				'permissions' => $request->getParam('permissions')
			]);

			if ($updatedGroup) {
				$this->jsonResponse->setData('updatedGroup', $updatedGroup);
			} else {
				$this->jsonResponse->setError($this->language->translate('cantUpdateGroup'));
			}
		}
	}

	public function validateFields($request, $id = false) {
		$rules = [
			'groupName' => [
				'label' => $this->language->translate('formLabelFName'),
				'rules' => v::notEmpty()->length(null, 25)->UsersGroupNameAvailable($id)
			]
		];

		if (!$this->auth->isOwner()) {
			$rules['groupLevel'] = [
				'label' => $this->language->translate('level'),
				'rules' => v::length(1, null)->intVal()->min($this->auth->group()->level)
			];
		}

		return $this->validator->check($request, $rules)->failed();
	}

	private function validatePermitions($permissions, $id = false) {
		$passed = true;

		if ($id && !$this->auth->isOwner()) {
			$group = Group::where('id', $id)->first();
			$originalPermissions = json_decode($group->permissions);
		}

		foreach (json_decode($permissions) as $key => $permission) {
			if (!$this->auth->isOwner()) {
				foreach ($permission as $action => $value) {
					if (!$this->auth->permissions($key, $action) && (($value && !$id) ||
						($id && $value != $originalPermissions->{$key}->{$action}))) {
						$this->jsonResponse->setError($this->language->translate('cantAssingRightTheatYourGroupDosNotHave'));
						$passed = false;
					}
				}
			}

			if (!$permission->view && in_array(true, (array)$permission)) {
				$this->jsonResponse->setError($this->language->translate('errorPermissionWithoutViewingRight', $this->siteSections[$key]['label']));
				$passed = false;
			}
		}

		return $passed;
	}

	public function delete($request, $response, $id) {
		if (!$group = $this->checkAuthLevel($id['id'])) {
			return $response->withJson($this->jsonResponse->getData());
		}

		if ($request->getParam('action') == 'move') {
			$group->first()->users()->update(['groupID' => $request->getParam('moveToID')]);
		} else if ($request->getParam('action') == 'delete') {
			$group->first()->users()->delete();
		}

		if (!$group->delete()) {
			$this->jsonResponse->setError($this->language->translate('cantDeleteGroup'));
			return false;
		}

		$this->paginationToJSON($this->getAll($request));
	}

	private function checkAuthLevel($id, $level = false)	{
		$group = Group::where('id', $id);

		if ($this->auth->isOwner()) {
			return $group;
		}

		if ($id) {
			if ($this->auth->user()->groupID == $id || !$group || $group->first()->level < $this->auth->group()->level) {
				$this->jsonResponse->setData('access', false);
				return false;
			}

			return $group;
		} else if ($level < $this->auth->group()->level) {
			$this->jsonResponse->setData('access', false);
			return false;
		}
		return true;
	}
}