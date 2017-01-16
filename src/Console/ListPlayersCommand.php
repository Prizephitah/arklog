<?php

namespace prizephitah\ArkLog\Console;

use prizephitah\ArkLog\Ark\Rcon;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

class ListPlayersCommand extends ConfigAwareCommand {

	/** @var  Rcon */
	protected $rcon;

	protected function configure() {
		$this
			->setName('listplayers')
			->setDescription('List the current players')
		;
	}

	protected function initialize(InputInterface $input, OutputInterface $output) {
		parent::initialize($input, $output);
		$this->rcon = new Rcon($this->config);
	}

	protected function execute(InputInterface $input, OutputInterface $output) {
		$players = $this->rcon->listPlayers();
		if (!empty($players)) {
			foreach ($players as $player) {
				$output->writeln($player);
			}
		} else {
			$output->writeln('No players connected');
		}
	}
}
