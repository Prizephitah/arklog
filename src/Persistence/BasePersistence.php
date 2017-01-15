<?php


namespace prizephitah\ArkLog\Persistence;


use Noodlehaus\Config;
use PDO;

abstract class BasePersistence {
	
	/** @var PDO */
	protected $pdo;
	
	/** @var Config */
	protected $config;
	
	public function __construct(Config $config) {
		$this->config = $config;
		$dsn = $config->get('database.driver', 'mysql')
			.':host='.$config->get('database.host', 'localhost')
			.';port='.$config->get('database.port', 3306)
			.';dbname='.$config->get('database.name')
			.';charset='.$config->get('database.charset', 'utf8')
		;
		$this->pdo = new PDO($dsn, $config->get('database.username'), $config->get('database.password'), [
			PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
			PDO::ATTR_DEFAULT_FETCH_MODE, PDO::FETCH_ASSOC,
			PDO::ATTR_EMULATE_PREPARES => false
		]);
	}
}