<?php

namespace prizephitah\ArkLog\Web;

use Interop\Container\ContainerInterface;
use Noodlehaus\Config;

class SlimAwareController {

  /** @var ContainerInterface */
  protected $container;

  /** @var Config */
  protected $config;

  public function __construct(ContainerInterface $container) {
    $this->container = $container;
    $this->config = $container->config;
  }
}
