<?php

$app -> get('/' , 'HomeController:index')->setName('home');

//$app->get('/info', 'InfoController:showMainInfo');

//  SIGN UP
$app -> get('/auth/signup' , 'AuthController:getSignUp')->setName('auth.signup');
$app -> post('/auth/signup' , 'AuthController:postSignUp');

//  SIGN IN
$app -> get('/auth/signin' , 'AuthController:getSignIn')->setName('auth.signin');
$app -> post('/auth/signin' , 'AuthController:postSignIn');

//SIGN OUT
$app -> get('/auth/signout' , 'AuthController:getSignOut')->setName('auth.signout');

//CHANGE PASS
$app -> get('/auth/password/change' , 'PasswordController:getChangePassword')->setName('auth.password.change');
$app -> post('/auth/password/change' , 'PasswordController:postChangePassword');