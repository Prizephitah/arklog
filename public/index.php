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

$slim->run();
