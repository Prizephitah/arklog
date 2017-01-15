<?php


namespace prizephitah\ArkLog\Console;


use Noodlehaus\Config;
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
	
	protected function initialize(InputInterface $input, OutputInterface $output) {
		$this->config = $config = new Config(__DIR__.'/../../config.yml');
	}
}