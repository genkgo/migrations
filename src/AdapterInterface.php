<?php
namespace Genkgo\Migrations;

/**
 * Interface AdapterInterface
 * @package Genkgo\Migrations
 */
interface AdapterInterface
{
    /**
     *
     * @param string $namespace
     * @param MigrationInterface $migration
     * @throws AlreadyMigratedException
     */
    
    public function upgrade($namespace, MigrationInterface $migration);
    
    /**
     *
     * @param string $namespace
     * @param MigrationInterface $migration
     * @throws AlreadyMigratedException
     */
    
    public function downgrade($namespace, MigrationInterface $migration);
    
    /**
     *
     */
    
    public function setup();
}
