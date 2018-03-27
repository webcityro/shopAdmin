<?php

namespace Storemaker\App\Auth;

use Storemaker\App\Models\Users\Account;
use Storemaker\App\Models\Users\UsersSession;

class Auth {
	protected $container,
				 $isLogin = false,
				 $account,
				 $data,
				 $group;

	function __construct($container)	{
		$this->container = $container;

		if (isset($_COOKIE['loginHash']) && !isset($_SESSION['userID'])) {
			$userSession = UsersSession::where('hash', $_COOKIE['loginHash'])->first();

			if (!$userSession) {
				setcookie('loginHash', $hash, -1, '/');
				$this->isLogin = false;
			}

			$_SESSION['userID'] = $userSession->userID;
		}

		if (isset($_SESSION['userID'])) {
			$this->account = Account::where('id', $_SESSION['userID']);
			$this->data = $this->account->first();
			$this->isLogin = ($this->data) ? true : false;

			if ($this->isLogin && !$this->isOwner()) {
				$this->group = $this->data->group()->first();
				$this->group->permissions = json_decode($this->group->permissions);
			}
		}
	}

	public function account() {
		return $this->account;
	}

	public function user() {
		return $this->data;
	}

	public function group() {
		return $this->group;
	}

	public function permissions($key, $action) {
		return ($this->isOwner()) ? true : $this->group->permissions->{$key}->{$action};
	}

	public function check()	{
		return $this->isLogin;
	}

	public function isOwner($id = false)	{
		return $this->container->config->get('system/ownerID') == ($id ?: $this->data->id);
	}

	public function attempt($user, $password, $rememberMe = false) {
		$user = Account::where('userName', $user)->orWhere('email', $user)->first();

		if (!$user) {
			$this->container->flash->addMessage('error', $this->container->language->translate('userNotFond'));
			return false;
		}

		if (password_verify($password, $user->password)) {
			if ($user->active == 0) {
				$this->container->flash->addMessage('warning', $this->container->language->translate('userNotActive'));
				return false;
			}

			if ($rememberMe) {
				$hash = bin2hex(random_bytes(64));

				UsersSession::create(['userID' => $user->id, 'hash' => $hash]);
				setcookie('loginHash', $hash, time()+60*60*24*30, '/');
			}

			$_SESSION['userID'] = $user->id;
			return true;
		} else {
			$this->container->flash->addMessage('error', $this->container->language->translate('wrongPassword'));
			return false;
		}
		return false;
	}

	public function logout() {
		if (isset($_COOKIE['loginHash'])) {
			UsersSession::where('hash', $_COOKIE['loginHash'])->delete();
			setcookie('loginHash', '', -1, '/');
		}
		unset($_SESSION['userID']);
	}
}