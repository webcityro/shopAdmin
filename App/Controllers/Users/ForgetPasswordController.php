<?php
namespace Storemaker\App\Controllers\Users;

use Storemaker\System\Libraries\Controller;
use Respect\Validation\Validator as v;
use Storemaker\App\Models\Users\Account;
use Storemaker\App\Models\Users\ForgetPassword;
use Carbon\Carbon;

class ForgetPasswordController extends Controller {
	private $tokenLiveTime = 30; // minutes

	function construct() {
		$this->language->load('Users/ForgetPassword');
	}

	public function index($request, $response) {
		return $this->view->render($response, 'users/forgetPasswordGetUser.twig');
	}

	public function sendEmail($request, $response) {
		$user = Account::where('userName', $request->getParam('login'))->orWhere('email', $request->getParam('login'))->first();

		if (!$user) {
			$this->flash->addMessage('error', $this->language->translate('userNotFond'));
			return $this->view->render($response, 'users/forgetPasswordGetUser.twig');
		}

		ForgetPassword::where('userID', $user->id)->delete();

		$newToken = md5(bin2hex(random_bytes(64)));

		ForgetPassword::create(['userID' => $user->id, 'token' => $newToken]);

		$this->email->addAddress($user->email, $user->userName);
		$this->email->Subject = $this->config->get('site/name').' - '.$this->language->translate('forgetPasswordEmailSubject');
		$this->email->Body = $this->view->fetch('users/forgetPasswordEmail.twig', ['linkURL' => rtrim($this->config->get('site/domain'), '/').$this->router->pathFor('users.accounts.forgetPassword.new', ['token' => $newToken]), 'expTime' => $this->tokenLiveTime]);

		if ($this->email->send()) {
			$this->flash->addMessage('success', $this->container->language->translate('forgetPasswordEmailSend', $user->email));
		} else {
			// log error
			// // $this->email->ErrorInfo;/*
			// var_dump($this->email->ErrorInfo);
			// die();*/
			$this->flash->addMessage('error', $this->container->language->translate('emailNotSend'));
			ForgetPassword::where('userID', $user->id)->delete();
		}

		return $response->withRedirect($this->router->pathFor('users.accounts.login'));
	}

	public function showChangePassword($request, $response, $token) {
		$token = $this->checkAndGetToken($token);

		if (!$token) {
			return $response->withRedirect($this->router->pathFor('users.accounts.login'));
		}

		return $this->view->render($response, 'users/forgetPasswordChange.twig', ['token' => $token->token]);
	}

	public function changePassword($request, $response, $token) {
		$token = $this->checkAndGetToken($token);

		if (!$token) {
			return $response->withRedirect($this->router->pathFor('users.accounts.login'));
		}

		if (empty($request->getParam('password'))) {
			return $this->view->render($response, 'users/forgetPasswordChange.twig', ['token' => $token->token, 'error' => $this->language->translate('errorEmptyPassword')]);
		}

		if (mb_strlen($request->getParam('password')) < 8) {
			return $this->view->render($response, 'users/forgetPasswordChange.twig', ['token' => $token->token, 'error' => $this->language->translate('errorShortPassword', 8)]);
		}

		$updatePassword = $token->user()->update([
			'password' => password_hash($request->getParam('password'), PASSWORD_DEFAULT)
		]);

		if ($updatePassword) {
			$this->flash->addMessage('success', $this->container->language->translate('changePasswordSuccess'));
		} else {
			$this->flash->addMessage('error', $this->container->language->translate('changePasswordFaild'));
		}

		ForgetPassword::where('token', $token)->delete();
		return $response->withRedirect($this->router->pathFor('users.accounts.login'));
	}

	private function checkAndGetToken($token)	{
		$token = ForgetPassword::where('token', $token);

		if (!$token) {
			$this->flash->addMessage('error', $this->container->language->translate('retesPasswordInvalidLink'));
			return false;
		}

		if ($token->first()->created_at->diffInMinutes(Carbon::now()) > $this->tokenLiveTime) {
			$this->flash->addMessage('error', $this->container->language->translate('retesPasswordExpiratedLink'));
			$token->delete();
			return false;
		}

		return $token->first();
	}
}