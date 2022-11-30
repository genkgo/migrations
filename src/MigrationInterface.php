<?php

declare(strict_types=1);

namespace Genkgo\Migrations;

interface MigrationInterface
{
    public const DIRECTION_UP = 1;
    public const DIRECTION_DOWN = 2;

    /**
     * @return void
     */
    public function up();

    /**
     * @return void
     */
    public function down();
    
    public function getName(): string;
}
