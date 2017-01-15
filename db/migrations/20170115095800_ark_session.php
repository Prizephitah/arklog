<?php

use Phinx\Migration\AbstractMigration;

class ArkSession extends AbstractMigration
{
    /**
     * Create the session table.
     *
     * The session table stores all known sessions for an Ark player.
     * Created and updated can be treated as start and stop for the session. However the session might still be in
     * progress.
     */
    public function change()
    {
		$table = $this->table('ark_session');
		$table
			->addColumn('player_id', 'integer')
			->addColumn('created', 'datetime')
			->addColumn('updated', 'datetime', ['null'=> true, 'default' => null])
			->addForeignKey('player_id', 'ark_player', 'id')
			->addIndex('created')
			->create()
		;
    }
}
