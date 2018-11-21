<?php
namespace Storemaker\App\Controllers\Users;
use Storemaker\System\Libraries\Controller;
use Respect\Validation\Validator as v;
use Storemaker\App\Models\Users\Account;
use Storemaker\App\Models\Users\Group;

class AccountsController extends Controller {
	function construct() {
		$this->language->load('Users/Accounts');
		$this->setPaginationPath('/users/accounts');
	}

	public function index($request, $response) {
		$users = Account::with('group')->paginate(10, ['*'], 'page', $request->getParam('page'));

		return $this->view->render($response, 'users/accounts.twig', [
			'usersList' => $users,
			'groupsList' => Group::where('storeID', $this->config->get('store/id'))->where('level', '>=', ($this->auth->isOwner() ? 0 : $this->auth->group()->level))->orderBy('level', 'ASC')->get(),
		]);
	}

	public function showProfile($request, $response, $args){
		$user = (isset($args['user'])) ? Account::where('id', $args['user'])->orWhere('userName', $args['user'])->orWhere('email', $args['user'])->first() : $this->auth->user();

		if (!$user) {
			$this->flash->addMessage('error', $this->language->translate('accountNotFond'));
			return $response->withRedirect($this->router->pathFor('home'));
		}

		if (!$this->auth->isOwner() && $this->auth->user()->id != $user->id && !$this->auth->permissions('users/profile', 'view')) {
			$this->flash->addMessage('error', $this->language->translate('userAccessDenied'));
			return $response->withRedirect($this->router->pathFor('home'));
		}

		if (!$this->auth->isOwner() && $this->auth->isOwner($user->id)) {
			$this->flash->addMessage('error', $this->language->translate('onleTheOwnerCanSeeHisProfile'));
			return $response->withRedirect($this->router->pathFor('home'));
		}

		return $this->view->render($response, 'users/profile.twig', ['user' => $user]);
	}

	public function updateProfile($request, $response) {
		if ($fields = $this->validation($request, $this->auth->user()->id)) {
			if (!$this->auth->account()->update($fields)) {
				$this->jsonResponse->setError($this->language->translate('updatedNotProfile'));
			}
		}
	}

	public function updatePassword($request, $response) {
		if ($fields = $this->validation($request, $this->auth->user()->id, true)) {
			if (!$this->auth->account()->update($fields)) {
				$this->jsonResponse->setError($this->language->translate('updatedNotPassword'));
			}
		}
	}

	public function showLogin($request, $response){
		return $this->view->render($response, 'users/login.twig');
	}

	public function runLogin($request, $response){
		if (empty($request->getParam('login')) || empty($request->getParam('password'))) {
			$this->flash->addMessage('error', $this->language->translate('noLoginOrPassword'));
			return $response->withRedirect($this->router->pathFor('users.accounts.login'));
		}

		$auth = $this->auth->attempt($request->getParam('login'), $request->getParam('password'), $request->getParam('rememberMe') == 'true');

		if ($auth) {
			return $response->withRedirect($this->router->pathFor('home'));
		}

		return $response->withRedirect($this->router->pathFor('users.accounts.login'));
	}

	public function logout($request, $response) {
		$this->auth->logout();
		return $response->withRedirect($this->router->pathFor('users.accounts.login'));
	}

	public function get($request, $response, $id) {
		if ($this->auth->isOwner($id['id']) && !$this->auth->isOwner()) {
			$this->jsonResponse->setData('access', false);
		} else {
			$user = Account::where('id', $id)->first();

			$this->checkGroupLevel($user->groupID);
			$this->jsonResponse->setData('user', $user);
		}
		return $response->withJson($this->jsonResponse->getData());
	}

	public function add($request, $response) {
		if ($fields = $this->validation($request)) {
			if ($newUser = Account::create($fields)) {
				$this->jsonResponse->setData('newUser', $newUser);
				$this->paginationToJSON(Account::paginate(10, ['*'], 'page', $request->getParam('page')));
			} else {
				$this->jsonResponse->setError($this->language->translate('canNotCreateUser'));
			}
		}
	}

	public function update($request, $response, $id) {
		if ($this->auth->isOwner($id['id']) && !$this->auth->isOwner()) {
			$this->jsonResponse->setData('access', false);
			return false;
		}

		if ($fields = $this->validation($request, $id)) {
			if ($updateUser = Account::where('id', $id)->update($fields)) {
				$this->jsonResponse->setData('updateUser', $updateUser);
			} else {
				$this->jsonResponse->setError($this->language->translate('canNotUpdateUser'));
			}
		}
	}

	public function delete($request, $response, $id) {
		if ($this->auth->isOwner($id['id'])) {
			$this->jsonResponse->setData('access', false);
			die();
		}

		$user = Account::where('id', $id)->first();

		if (!$this->checkGroupLevel($user->groupID)) {
			return false;
		}

		$deleteUser = Account::where('id', $id)->delete();

		if (!$deleteUser) {
			$this->jsonResponse->setError($this->language->translate('canNotDeleteUser'));
			return false;
		}
		$this->paginationToJSON(Account::paginate(10, ['*'], 'page', $request->getParam('page')));
	}

	private function checkGroupLevel($id) {
		if ($this->auth->isOwner()) {
			return true;
		}
		$group = Group::where('id', $id);
		if ($group->count() == 0 || $group->first()->level < $this->auth->group()->level) {
			$this->jsonResponse->setData('access', false);
			return false;
		}
		return true;
	}

	private function validation($request, $id = false, $updatePassword = false)	{
		$validation = (!$updatePassword) ? [
			'fName' => [
				'label' => $this->language->translate('formLabelFName'),
				'rules' => v::notEmpty()->length(3, 25)->alpha()->noWhiteSpace()
			],
			'lName' => [
				'label' => $this->language->translate('formLabelLName'),
				'rules' => v::optional(v::length(null, 25)->alpha())
			],
			'email' => [
				'label' => $this->language->translate('formLabelEmail'),
				'rules' => v::notEmpty()->email()->EmailAvailable($id)
			],
			'sex' => [
				'label' => $this->language->translate('formLabelSexValidation'),
				'rules' => v::notEmpty()
			]
		] : [];

		$returnFields = (!$updatePassword) ? [
			'fName' => $request->getParam('fName'),
			'lName' => $request->getParam('lName'),
			'email' => $request->getParam('email'),
			'sex' => $request->getParam('sex')
		] : [];

		if (isset($request->getParams()['userName'])) {
			$validation['userName'] = [
				'label' => $this->language->translate('formLabelUserName'),
				'rules' => v::notEmpty()->length(4, 32)->alnum('-_.')->noWhiteSpace()->UserNameAvailable()
			];
			$returnFields['userName'] = $request->getParam('userName');
		}

		if (isset($request->getParams()['password'])) {
			$validation['password'] = [
				'label' => $this->language->translate('formLabelPassword'),
				'rules' => v::notEmpty()->length(8, null)
			];
			$returnFields['password'] = password_hash($request->getParam('password'), PASSWORD_DEFAULT);
		}

		if (isset($request->getParams()['oldPassword'])) {
			$validation['oldPassword'] = [
				'label' => $this->language->translate('formLabelCurrentPassword'),
				'rules' => v::notEmpty()->CheckCurrentPassword($this->auth->account()->first())
			];
		}

		if ($this->auth->user()->id != $id) {
			if (isset($request->getParams()['groupID'])) {
				$validation['groupID'] = [
					'label' => $this->language->translate('userGroups'),
					'rules' => v::length(1, null)
				];

				if ($id) {
					$user = Account::where('id', $id)->first();

					if (!$this->checkGroupLevel($user->groupID)) {
						return false;
					}
				}

				if (!$this->checkGroupLevel($request->getParam('groupID'))) {
					return false;
				}

				$returnFields['groupID'] = $request->getParam('groupID');
			}

			if (isset($request->getParams()['status'])) {
				$validation['status'] = [
					'label' => $this->language->translate('status'),
					'rules' => v::length(1, null)
				];
				$returnFields['active'] = $request->getParam('status');
			}
		}

		return !$this->validator->check($request, $validation)->failed() ? $returnFields : false;
	}
}