<?php

use Respect\Validation\Validator as v;

session_start();

require  __DIR__ . '/../vendor/autoload.php';
//require __DIR__ . '/../bootstrap/config/cli-config.php';

use Doctrine\ORM\Tools\Setup;
use Doctrine\ORM\EntityManager;

require __DIR__ . '/../bootstrap/config/config.php';

$app = new \Slim\App([
    'settings' => [
        'displayErrorDetails' => true,
        'db' => $dbConnector,
    ],
]);


$container = $app -> getContainer();

$config = \Doctrine\ORM\Tools\Setup::createAnnotationMetadataConfiguration(
    $dbConnector['meta']['entity_path'], $dbConnector['meta']['auto_generate_proxies'], $dbConnector['meta']['proxy_dir'], $dbConnector['meta']['cache'], false
);

$em = \Doctrine\ORM\EntityManager::create(
    $dbConnector['connection'], $config
);
$container['em'] = $em;

$container['db'] = function ($container) {
    return $container['settings']['db']['connection']['driver'];
};

//$container['settings']['db'] = $dbConnector['meta'];


//      DOCTRINE
//$entityManager = GetEntityManager();




$container['auth'] = function ($container) {
    return new \App\Auth\Auth($container);
};

$container['view'] = function ($container) {
    $view = new \Slim\Views\Twig(__DIR__ . '/../resources/views', [

        'cache' => false,
    ]);

    $view -> addExtension(new \Slim\Views\TwigExtension(
        $container -> router,
        $container -> request -> getUri()
    ));
    $view->addExtension(new Twig_Extension_Debug());

    $view->getEnvironment()->addGlobal('auth', [

        'check' => $container->auth->check(),
        'user' => $container->auth->user(),
    ]);

    $view->getEnvironment()->addGlobal('flash', $container->flash);

    return $view;

};

$container['validator'] = function ($container) {
    return new App\Validation\Validator;
};

$container['HomeController'] = function ($container) {
    return new \App\Controllers\HomeController($container);
};

$container['AuthController'] = function ($container) {
    return new \App\Controllers\Auth\AuthController($container);
};

$container['PasswordController'] = function ($container) {
    return new \App\Controllers\Auth\PasswordController($container);
};

$container['flash'] = function ($container) {
    return new \Slim\Flash\Messages;
};

$container['EmailAvailable'] = function ($container) {
    return new \App\Validation\Rules\EmailAvailable($container);
};

$container['csrf'] = function ($container) {
    return new \Slim\Csrf\Guard;
};

$app->add(new App\Middleware\ValidationErrorsMiddleware($container));
$app->add(new App\Middleware\OldInputMiddleware($container));
$app->add(new App\Middleware\CsrfViewMiddleware($container));


//$app->add($container->csrf);

v::with('App\\Validation\\Rules\\');


//$container['InfoController'] = function ($container) {
  //  return new \App\Controllers\InfoController($container);
//};

require __DIR__ . '/../app/routes.php';