<?php

declare(strict_types=1);

namespace Genkgo\Migrations\Adapters;

final class PdoSqliteAdapter extends AbstractPdoAdapter
{
    protected function createTableIfNotExists(): void
    {
        $this->getPdo()->exec(
            "CREATE TABLE IF NOT EXISTS {$this->table} (id INTEGER PRIMARY KEY ASC, name TEXT, migration TEXT, direction INTEGER, migrated_on TEXT)"
        );
    }
}
