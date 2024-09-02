<?php

declare(strict_types=1);

namespace Genkgo\Migrations;

interface ClassLoaderInterface
{
    public function newInstance(string $className): MigrationInterface;
}
