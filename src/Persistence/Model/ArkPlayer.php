<?php


namespace prizephitah\ArkLog\Persistence\Model;


use prizephitah\ArkLog\Ark\Model\Player;

class ArkPlayer extends Player {
	
	/** @var  int */
	protected $id;
	
	/** @var  \DateTime */
	protected $created;
	
	/** @var  \DateTime|null */
	protected $updated;
	
	/**
	 * ArkPlayer constructor.
	 * @param int $id
	 * @param string $nickName
	 * @param int $steamId
	 * @param \DateTime $created
	 * @param \DateTime|null $updated
	 */
	public function __construct($id, $nickName, $steamId, \DateTime $created, \DateTime $updated = null) {
		parent::__construct($nickName, $steamId);
		$this->id = $id;
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
	
	/**
	 * @inheritdoc
	 */
	public function jsonSerialize() {
		$data = parent::jsonSerialize();
		$data['id'] = $this->getId();
		$data['created'] = $this->getCreated()->format(\DateTime::ISO8601);
		$data['updated'] = $this->getUpdated() instanceof \DateTime
			? $this->getUpdated()->format(\DateTime::ISO8601) : null;
		return $data;
	}
	
	/**
	 * @return string
	 */
	public function __toString() {
		return $this->getNickName().' (ArkLog# '.$this->getId().' Steam#'.$this->getSteamId().')';
	}
}