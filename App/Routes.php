<?php

use Storemaker\App\Middleware\GuestMiddleware;
use Storemaker\App\Middleware\AuthMiddleware;
use Storemaker\App\Middleware\UserPermissionsMiddleware;

$app->group('', function() use($container) {
	$this->get('/', 'HomeController:index')->setName('home');

	/* users */
	$this->get('/users', 'AccountsController:index')->setName('users.accounts')->add(new UserPermissionsMiddleware($container, 'users/accounts', 'view'));
	$this->post('/users/add', 'AccountsController:add')->setName('users.accounts.add')->add(new UserPermissionsMiddleware($container, 'users/accounts', 'add'));
	$this->get('/users/get/{id}', 'AccountsController:get')->setName('users.accounts.get')->add(new UserPermissionsMiddleware($container, 'users/accounts', 'edit'));
	$this->post('/users/update/{id}', 'AccountsController:update')->setName('users.accounts.update')->add(new UserPermissionsMiddleware($container, 'users/accounts', 'edit'));
	$this->post('/users/delete/{id}', 'AccountsController:delete')->setName('users.accounts.delete')->add(new UserPermissionsMiddleware($container, 'users/accounts', 'delete'));
	$this->get('/users/profile[/{user}]', 'AccountsController:showProfile')->setName('users.profile');
	$this->post('/users/profile/update', 'AccountsController:updateProfile')->setName('users.profile.update');
	$this->post('/users/profile/changePassword', 'AccountsController:updatePassword')->setName('users.profile.changePassword');

	$this->get('/users/logout', 'AccountsController:logout')->setName('users.accounts.logout');

	/* users groups */
	$this->get('/users/groups', 'UsersGroupsController:index')->setName('users.groups')->add(new UserPermissionsMiddleware($container, 'users/groups', 'view'));
	$this->post('/users/groups/add', 'UsersGroupsController:add')->setName('users.groups.add')->add(new UserPermissionsMiddleware($container, 'users/groups', 'add'));
	$this->get('/users/groups/get/{id}', 'UsersGroupsController:get')->setName('users.groups.get')->add(new UserPermissionsMiddleware($container, 'users/groups', 'edit'));
	$this->post('/users/groups/update/{id}', 'UsersGroupsController:update')->setName('users.groups.update')->add(new UserPermissionsMiddleware($container, 'users/groups', 'edit'));
	$this->post('/users/groups/delete/{id}', 'UsersGroupsController:delete')->setName('users.groups.delete')->add(new UserPermissionsMiddleware($container, 'users/groups', 'delete'));
})->add(new AuthMiddleware($container));

$app->group('', function() use($container) {
	/* users login */
	$this->get('/users/login', 'AccountsController:showLogin')->setName('users.accounts.login');
	$this->post('/users/login', 'AccountsController:runLogin');

	/* reset user's password */
	$this->get('/users/forget_password', 'ForgetPasswordController:index')->setName('users.accounts.forgetPassword');
	$this->post('/users/forget_password', 'ForgetPasswordController:sendEmail');
	$this->get('/users/forget_password/new/{token}', 'ForgetPasswordController:showChangePassword')->setName('users.accounts.forgetPassword.new');
	$this->post('/users/forget_password/new/{token}', 'ForgetPasswordController:changePassword');
})->add(new GuestMiddleware($container));

