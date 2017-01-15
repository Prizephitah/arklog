<?php


namespace prizephitah\ArkLog\Console;


use prizephitah\ArkLog\Ark\Rcon;
use prizephitah\ArkLog\Persistence\ArkPlayerController;
use prizephitah\ArkLog\Persistence\ArkSessionController;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

class ArkLogCommand extends ConfigAwareCommand {
	
	/** @var  Rcon */
	protected $rcon;
	
	/** @var  ArkPlayerController */
	protected $playerController;
	
	/** @var  ArkSessionController */
	protected $sessionController;
	
	protected function configure() {
		$this
			->setName('log')
			->setDescription('The command to schedule to run at the configured interval to log all player sessions.')
		;
	}
	
	protected function initialize(InputInterface $input, OutputInterface $output) {
		parent::initialize($input, $output);
		$this->rcon = new Rcon($this->config);
		$this->playerController = new ArkPlayerController($this->config);
		$this->sessionController = new ArkSessionController($this->config);
	}
	
	protected function execute(InputInterface $input, OutputInterface $output) {
		$players = $this->rcon->listPlayers();
		foreach ($players as $player) {
			$player = $this->playerController->register($player);
			$this->sessionController->register($player);
		}
	}
}