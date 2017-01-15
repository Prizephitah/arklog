<?php

namespace prizephitah\ArkLog\Ark;

use gries\Rcon\Messenger;
use gries\Rcon\MessengerFactory;
use Noodlehaus\Config;

class Rcon {
	
	/**
	 * @var Messenger
	 */
	protected $messenger;
	
	public function __construct(Config $config) {
		$this->messenger = MessengerFactory::create(
			$config->get('rcon.host', 'localhost'),
			$config->get('rcon.port', 27020),
			$config->get('rcon.password', null)
		);
	}
	
	/**
	 * @param string $message
	 * @param callable|null $callable
	 * @return string
	 */
	public function send($message, $callable = null) {
		return $this->messenger->send($message, $callable);
	}
	
	public function listPlayers() {
		return $this->send(Command::LIST_PLAYERS);
	}
}