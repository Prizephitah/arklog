<?php


namespace prizephitah\ArkLog\Ark\Model;


class Player implements \JsonSerializable {
	
	/** @var  string */
	protected $nickName;
	
	/** @var  int */
	protected $steamId;
	
	/**
	 * Player constructor.
	 * @param string $nickName
	 * @param int $steamId
	 */
	public function __construct($nickName, $steamId) {
		$this->nickName = $nickName;
		$this->steamId = $steamId;
	}
	
	/**
	 * @return string
	 */
	public function getNickName() {
		return $this->nickName;
	}
	
	/**
	 * @return int
	 */
	public function getSteamId() {
		return $this->steamId;
	}
	
	/**
	 * @inheritdoc
	 */
	public function jsonSerialize() {
		return [
			'nickName' => $this->getNickName(),
			'steamId' => $this->getSteamId()
		];
	}
	
	/**
	 * @return string
	 */
	public function __toString() {
		return $this->getNickName().' (Steam#: '.$this->getSteamId().')';
	}
}