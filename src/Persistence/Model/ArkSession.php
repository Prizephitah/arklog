<?php


namespace prizephitah\ArkLog\Persistence\Model;


class ArkSession implements \JsonSerializable {
	
	/** @var  int */
	protected $id;
	
	/** @var  int */
	protected $playerId;
	
	/** @var  \DateTime */
	protected $created;
	
	/** @var  \DateTime|null */
	protected $updated;
	
	public function __construct($id, $playerId, \DateTime $created, \DateTime $updated = null) {
		$this->id = (int)$id;
		$this->playerId = (int)$playerId;
		$this->created = $created;
		$this->updated = $updated;
	}
	
	/**
	 * @return int
	 */
	public function getId() {
		return $this->id;
	}
	
	/**
	 * @return int
	 */
	public function getPlayerId() {
		return $this->playerId;
	}
	
	/**
	 * @return \DateTime
	 */
	public function getCreated() {
		return $this->created;
	}
	
	/**
	 * @return \DateTime|null
	 */
	public function getUpdated() {
		return $this->updated;
	}
	
	public function jsonSerialize() {
		return [
			'id' => $this->getId(),
			'playerId' => $this->getPlayerId(),
			'created' => $this->getCreated()->format(\DateTime::ISO8601),
			'updated' => $this->getUpdated() instanceof \DateTime
				? $this->getUpdated()->format(\DateTime::ISO8601) : null
		];
	}
	
	public function __toString() {
		return 'User #'.$this->getPlayerId().' From: '.$this->getCreated()->format(\DateTime::ISO8601)
			.' To: '.($this->getUpdated() instanceof \DateTime ? $this->getUpdated()->format(\DateTime::ISO8601) : 'now');
	}
}