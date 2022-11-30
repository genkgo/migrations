<?php

declare(strict_types=1);

namespace Genkgo\Migrations;

interface AdapterInterface
{
    public function upgrade(string $namespace, MigrationInterface $migration): void;
    
    public function downgrade(string $namespace, MigrationInterface $migration): void;
    
    public function setup(): void;
}
