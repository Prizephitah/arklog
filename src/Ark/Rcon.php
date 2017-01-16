<?php

namespace prizephitah\ArkLog\Ark;

use gries\Rcon\Messenger;
use gries\Rcon\MessengerFactory;
use Noodlehaus\Config;
use prizephitah\ArkLog\Ark\Model\Player;

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
	
	/**
	 * Lists the currently online players
	 * @return Player[]
	 */
	public function listPlayers() {
		$playerData = $this->send(Command::LIST_PLAYERS);
		$matches = [];
		$players = [];
		preg_match_all('/\\d+\\.\\s+(.*?),\\s+(\\d+)/', $playerData, $metches);
		foreach ($matches as $match) {
			$players[] = new Player($match[1], $match[2]);
		}
		return $players;
	}
}