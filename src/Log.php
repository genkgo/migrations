<?php
namespace Genkgo\Migrations;

use ArrayIterator;
use Countable;

/**
 * Class Log
 * @package Genkgo\Migrations
 */
class Log implements \IteratorAggregate, \Countable
{
    /**
     * @var array
     */
    private $log = [];

    /**
     * @param MigrationInterface $migration
     */
    
    public function attach(MigrationInterface $migration)
    {
        $this->log[] = $migration;
    }

    /**
     * @return ArrayIterator|\Traversable
     */
    
    public function getIterator()
    {
        return new ArrayIterator($this->log);
    }

    /**
     * @return int
     */
    
    public function count()
    {
        return count($this->log);
    }
}
