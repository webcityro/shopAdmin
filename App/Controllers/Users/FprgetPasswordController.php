<?php
namespace Storemaker\App\Controllers\Users;
use Storemaker\System\Libraries\Controller;
use Respect\Validation\Validator as v;
use Storemaker\App\Models\Users\Account;
use Storemaker\App\Models\Users\Group;

class ForgetPasswordController extends Controller {
	function construct() {
		$this->language->load('Accounts');
	}

	public function index($request, $response) {
		$users = Account::with('group')->paginate(10, ['*'], 'page', $request->getParam('page'));

		return $this->view->render($response, 'users/forgetPasswordGetUser.twig');
	}
}