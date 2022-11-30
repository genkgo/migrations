<?php

declare(strict_types=1);

namespace Genkgo\Migrations;

abstract class AbstractMigration implements MigrationInterface
{
    /**
     * @return void
     */
    abstract public function up();

    /**
     * @return void
     */
    abstract public function down();
    
    public function getName(): string
    {
        $parts = \explode('\\', $this::class);
        return \end($parts);
    }
}
