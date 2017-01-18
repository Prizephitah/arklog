<?php

namespace prizephitah\ArkLog\Web;

use Interop\Container\ContainerInterface;
use Noodlehaus\Config;
use Slim\Views\Twig;

class SlimAwareController {

  /** @var ContainerInterface */
  protected $container;

  /** @var Config */
  protected $config;

  /** @var Twig */
  protected $view;

  public function __construct(ContainerInterface $container) {
    $this->container = $container;
    $this->config = $container->config;
    $this->view = $container->view;
    $this->view['config'] = $this->config;
  }
}
