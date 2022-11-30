<?php

declare(strict_types=1);

use Genkgo\Migrations\AbstractMigration;

final class migration_2014_11_13_11_55 extends AbstractMigration
{
    private string $executed = '';

    public function up()
    {
        $this->executed = 'up';
    }

    public function down()
    {
        $this->executed = 'down';
    }

    public function getExecuted(): string
    {
        return $this->executed;
    }
}
