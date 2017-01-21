<?php

use Noodlehaus\Config;
use prizephitah\ArkLog\Web\HomeController;
use prizephitah\ArkLog\Web\Security\LoginController;
use prizephitah\ArkLog\Web\Security\SimplePasswordAuthenticationMiddleware;

require_once '../vendor/autoload.php';

$config = new Config('../config.yml');
$slimConfig = [
  'settings' => [
    'displayErrorDetails' => $config->get('debug'),
    'routerCacheFile' => $config->get('cache') ? $config->get('cache').'/routes' : false
  ]
];

$slim = new \Slim\App($slimConfig);

$container = $slim->getContainer();
$container['config'] = function ($container) use ($config) {
  return $config;
};
$container['view'] = function ($container) use ($config) {
    $view = new \Slim\Views\Twig('../src/Web/View', [
        'cache' => $config->get('cache') == false ? false : __DIR__.'/../'.$config->get('cache'),
        'debug' => $config->get('debug')
    ]);

    // Instantiate and add Slim specific extension
    $basePath = rtrim(str_ireplace('index.php', '', $container['request']->getUri()->getBasePath()), '/');
    $view->addExtension(new Slim\Views\TwigExtension($container['router'], $basePath));
    $view->addExtension(new Twig_Extension_Debug());

    return $view;
};
$container['session'] = function ($container) {
  return new \SlimSession\Helper;
};

$slim->add(new \Slim\Middleware\Session([
  'name' => 'ARKLOG_SESSION',
  'autorefresh' => true,
  'lifetime' => '20 minutes'
]));

$slim->get('/', HomeController::class.':home')
  ->setName('home')
  ->add(new SimplePasswordAuthenticationMiddleware($container));
$slim->get('/login/', LoginController::class.':login')
  ->setName('login');
$slim->post('/authenticate/', LoginController::class.':authenticate')
  ->setName('authenticate');

$slim->run();
