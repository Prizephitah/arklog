<?php

namespace prizephitah\ArkLog\Console;

use gries\Rcon\Messenger;
use gries\Rcon\MessengerFactory;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Output\OutputInterface;

class ListPlayersCommand extends Command {
	
	/** @var  Messenger */
	protected $messenger;
	
	protected function configure() {
		$this
			->setName('listplayers')
			->setDescription('List the current players')
			->addArgument('host', InputArgument::REQUIRED, 'The URL or IP of the host to connect to')
			->addOption('port', 'p', InputOption::VALUE_REQUIRED, 'The port to use for rcon', 27020)
			->addOption('password', null, InputOption::VALUE_REQUIRED, 'The password of to authenticate with')
		;
	}
	
	protected function initialize(InputInterface $input, OutputInterface $output) {
		$this->messenger = MessengerFactory::create(
			$input->getArgument('host'),
			$input->getOption('port'),
			$input->getOption('password')
		);
	}
	
	protected function execute(InputInterface $input, OutputInterface $output) {
		$response = $this->messenger->send('listplayers');
		$output->writeln($response);
	}
}