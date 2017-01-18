<?php

use Noodlehaus\Config;
use prizephitah\ArkLog\Web\HomeController;

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

$slim->get('/', HomeController::class.':home')->setName('home');

$slim->run();
