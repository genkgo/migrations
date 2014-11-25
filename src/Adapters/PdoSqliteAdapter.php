<?php
namespace Genkgo\Migrations\Adapters;

/**
 * Class PdoSqliteAdapter
 * @package Genkgo\Migrations\Adapters
 */
class PdoSqliteAdapter extends AbstractPdoAdapter
{
    /**
     *
     */
    protected function createTableIfNotExists()
    {
        $this->getPdo()->exec(
            'CREATE TABLE IF NOT EXISTS migrations(id INTEGER PRIMARY KEY ASC, name TEXT, migration TEXT, direction INTEGER, migrated_on TEXT)'
        );
    }
}
