<?php

use Noodlehaus\Config;

require_once __DIR__.'/vendor/autoload.php';

$config = new Config(__DIR__.'/config.yml');

return [
	'paths' => [
		'migrations' => __DIR__.'/db/migrations',
		'seeds' => __DIR__.'/db/seeds'
	],
	'environments' => [
		'default_migration_table' => 'phinxlog',
		'default_database' => 'development',
		'development' => [
			'adapter' => $config->get('database.driver'),
			'host' => $config->get('database.host'),
			'name' => $config->get('database.name'),
			'user' => $config->get('database.username'),
			'pass' => $config->get('database.password'),
			'port' => $config->get('database.port'),
			'charset' => $config->get('database.charset')
		]
	]
];