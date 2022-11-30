<?php

declare(strict_types=1);

use Genkgo\Migrations\AbstractMigration;

class migration_with_callback extends AbstractMigration
{
    private mixed $someInjection;
    
    public function __construct(mixed $someInjection)
    {
        $this->someInjection = $someInjection;
    }

    public function up()
    {
    }

    public function down()
    {
    }
    
    public function getSomeInjection(): mixed
    {
        return $this->someInjection;
    }
}
