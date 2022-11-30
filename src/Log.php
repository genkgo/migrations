<?php

declare(strict_types=1);

namespace Genkgo\Migrations;

/**
 * @implements \IteratorAggregate<MigrationInterface>
 */
final class Log implements \IteratorAggregate, \Countable
{
    /**
     * @var array<int, MigrationInterface>
     */
    private array $log = [];

    public function attach(MigrationInterface $migration): void
    {
        $this->log[] = $migration;
    }

    /**
     * @return \ArrayIterator<int, MigrationInterface>
     */
    public function getIterator(): \ArrayIterator
    {
        return new \ArrayIterator($this->log);
    }

    public function count(): int
    {
        return \count($this->log);
    }
}
