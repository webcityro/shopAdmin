<?php
/*
/users/profile{/userName/param2}
*/
$app->get('/', 'Storemaker\\App\\Controllers\\Home:index');
$app->get('/users', 'Storemaker\\App\\Controllers\\Users\\Users:index');
$app->get('/users/groups', 'Storemaker\\App\\Controllers\\Users\\Groups:index');