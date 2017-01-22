<?php


namespace prizephitah\ArkLog\Console;

use Noodlehaus\Config;
use Psr\Log\LoggerInterface;
use Psr\Log\NullLogger;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

/**
 * Abstract class for commands that needs the config. I.e. most commands.
 * @package prizephitah\ArkLog\Console
 */
abstract class ConfigAwareCommand extends Command {

	/** @var Config */
	protected $config;

	/** @var LoggerInterface */
	protected $logger;

	public function __construct(Config $config, LoggerInterface $logger = null) {
		parent::__construct();
		if ($logger instanceof LoggerInterface) {
			$this->logger = $logger;
		} else {
			$this->logger = new NullLogger();
		}
		$this->config = $config;
	}
}
