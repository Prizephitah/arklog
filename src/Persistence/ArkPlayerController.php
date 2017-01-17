<?php


namespace prizephitah\ArkLog\Persistence;


use prizephitah\ArkLog\Ark\Model\Player;
use prizephitah\ArkLog\Persistence\Model\ArkPlayer;

class ArkPlayerController extends BasePersistence {

	public function register(Player $player) {
		$existingPlayer = $this->getPlayerBySteamId($player->getSteamId());
		if (!$existingPlayer) {
			$existingPlayer = $this->create($player);
		} else {
			$existingPlayer = $this->update($existingPlayer->getId(), $player);
		}

		return $existingPlayer;
	}

	/**
	 * Gets a registered player by their id.
	 * @param int $id
	 * @return null|ArkPlayer Returns null if not found.
	 */
	public function getPlayer($id) {
		$stmt = $this->pdo->prepare('
			SELECT id, steam_id, nickname, created, updated
			FROM ark_player
			WHERE id = :id
		');
		$stmt->execute([':id' => $id]);
		$playerData = $stmt->fetch();
		if (!$playerData) {
			return null;
		}

		return new ArkPlayer(
			$playerData['id'],
			$playerData['nickname'],
			$playerData['steam_id'],
			new \DateTime($playerData['created']),
			$playerData['updated'] != null ? new \DateTime($playerData['updated']) : null
		);
	}

	/**
	 * Gets a registered player by their Steam id.
	 * @param int $steamId
	 * @return null|ArkPlayer Returns null if not found.
	 */
	public function getPlayerBySteamId($steamId) {
		$stmt = $this->pdo->prepare('
			SELECT id
			FROM ark_player
			WHERE steam_id = :steamId
		');
		$stmt->execute([':steamId' => $steamId]);
		$playerId = $stmt->fetchColumn();
		if (!$playerId) {
			return null;
		}
		return $this->getPlayer($playerId);
	}

	/**
	 * Persists a new and previously unknown ArkPlayer.
	 * @param Player $player
	 * @return ArkPlayer
	 */
	public function create(Player $player) {
		$stmt = $this->pdo->prepare('
			INSERT INTO ark_player
			(steam_id, nickname, created)
			VALUES (:steamId, :nickName, NOW())
		');
		$stmt->execute([':steamId' => $player->getSteamId(), ':nickName' => $player->getNickName()]);
		return $this->getPlayerBySteamId($player->getSteamId());
	}

	public function update($id, Player $player) {
		$stmt = $this->pdo->prepare('
			UPDATE ark_player
			SET
				nickname = :nickName,
				updated = NOW()
			WHERE id = :id
		');
		$stmt->execute([':id' => $id, ':nickName' => $player->getNickName()]);
		return $this->getPlayer($id);
	}
}
