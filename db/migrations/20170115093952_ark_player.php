<?php

use Phinx\Migration\AbstractMigration;

class ArkPlayer extends AbstractMigration
{
	/**
	 * Create the player table.
	 *
	 * The player table stores all known players.
	 */
    public function change()
    {
		$table = $this->table('ark_player');
		$table
			->addColumn('steam_id', 'biginteger')
			->addColumn('nickname', 'string')
			->addColumn('created', 'datetime')
			->addColumn('updated', 'datetime', ['null'=> true, 'default' => null])
			->addIndex('steam_id', ['unique' => true])
			->create()
		;
    }
}
