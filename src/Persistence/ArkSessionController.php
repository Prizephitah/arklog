<?php


namespace prizephitah\ArkLog\Persistence;


use prizephitah\ArkLog\Persistence\Model\ArkPlayer;
use prizephitah\ArkLog\Persistence\Model\ArkSession;

class ArkSessionController extends BasePersistence {

	/**
	 * Registers a session for a player.
	 *
	 * Updates an existing or creates a new if needed.
	 * @param ArkPlayer $player
	 * @return null|ArkSession
	 */
	public function register(ArkPlayer $player) {
		$session = $this->getCurrentSession($player->getId());
		if (!$session) {
			$session = $this->create($player->getId());
		} else {
			$session = $this->update($session);
		}
		return $session;
	}

	/**
	 * Gets all recent sessions.
	 * @param  DateTime $limit The limit of what's recent. Defaults to the last 24 hours.
	 * @return ArkSession[]
	 */
	public function getRecentSessions(\DateTime $limit = null) {
		if ($limit === null) {
			$limit = new \DateTime('-24 hours');
		}
		$stmt = $this->pdo->prepare('
			SELECT id, player_id, created, updated
			FROM ark_session
			WHERE created >= :limitCreated OR updated >= :limitUpdated
		');
		$stmt->execute([
			':limitCreated' => $limit->format('Y-m-d H:i:s'),
			':limitUpdated' => $limit->format('Y-m-d H:i:s')
		]);

		$sessions = [];
		while ($sessionData = $stmt->fetch()) {
			$sessions[] = new ArkSession(
				$sessionData['id'],
				$sessionData['player_id'],
				new \DateTime($sessionData['created']),
				new \DateTime($sessionData['updated'])
			);
		}

		return $sessions;
	}

	/**
	 * Gets a session for the specified player updated within the configured log interval.
	 *
	 * Log interval automatically adjusted for small deviations in run time.
	 * @param int $playerId
	 * @return null|ArkSession Returns null if not found.
	 */
	public function getCurrentSession($playerId) {
		$stmt = $this->pdo->prepare('
			SELECT id, player_id, created, updated
			FROM ark_session
			WHERE
				player_id = :playerId
				AND updated >= :updated
			ORDER BY updated, created DESC
			LIMIT 1
		');
		$lastUpdated = $this->config->get('log_interval') * 1.5;
		$lastUpdated = new \DateTime('-'.$lastUpdated.' minutes');
		$stmt->execute([':playerId' => $playerId, ':updated' => $lastUpdated->format('Y-m-d H:i:s')]);
		$sessionData = $stmt->fetch();
		if (!$sessionData) {
			return null;
		}

		return new ArkSession(
			$sessionData['id'],
			$sessionData['player_id'],
			new \DateTime($sessionData['created']),
			$sessionData['updated'] != null ? new \DateTime($sessionData['updated']) : null
		);
	}

	/**
	 * Creates a new session for the specified player id.
	 * @param int $playerId
	 * @return ArkSession
	 */
	public function create($playerId) {
		$stmt = $this->pdo->prepare('
			INSERT INTO ark_session
			(player_id, created, updated)
			VALUES (:playerId, :created, :updated)
		');
		$created = new \DateTime();
		$updated = new \DateTime('+'.($this->config->get('log_interval') / 2).' minutes');
		$stmt->execute([
			':playerId' => $playerId,
			':created' => $created->format('Y-m-d H:i:s'),
			':updated' => $updated->format('Y-m-d H:i:s')
		]);
		return $this->getCurrentSession($playerId);
	}

	/**
	 * Updates a session.
	 * @param ArkSession $session
	 * @return null|ArkSession
	 */
	public function update(ArkSession $session) {
		$stmt = $this->pdo->prepare('
			UPDATE ark_session
			SET updated = NOW()
			WHERE id = :id
		');
		$stmt->execute([':id' => $session->getId()]);
		return $this->getCurrentSession($session->getPlayerId());
	}
}
