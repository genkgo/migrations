<?php

declare(strict_types=1);

namespace Genkgo\TestMigrations\Integration;

use Genkgo\TestMigrations\AbstractTestCase;
use PDO;
use InvalidArgumentException;
use Genkgo\Migrations\Adapters\PdoSqliteAdapter;
use Genkgo\Migrations\Factory;
use Genkgo\Migrations\MigrationInterface;

class MigrateTest extends AbstractTestCase
{
    private PdoSqliteAdapter $adapter;
    private Factory $factory;

    public function setUp(): void
    {
        $pdo = new PDO('sqlite::memory:');
        $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
        
        $adapter = new PdoSqliteAdapter($pdo);
        $this->factory = new Factory($adapter);
        $this->adapter = $adapter;
    }

    public function testUpgrade(): void
    {
        $list = $this->factory->newListFromDirectory(__DIR__.'/migrations');
        $this->assertCount(2, $list);

        $result = $list->migrate();
        $this->assertCount(2, $result);
        $this->assertEquals(2, $this->adapter->getNumberOfMigrations());

        /** @var \migration_2014_11_13_11_55|\migration_2014_11_13_12_05 $migration */
        foreach ($result as $migration) {
            $this->assertEquals('up', $migration->getExecuted());
        }
        
        $result = $list->migrate();
        $this->assertCount(0, $result);
    }

    public function testDowngrade(): void
    {
        $list = $this->factory->newListFromDirectory(__DIR__.'/migrations');
        $list->migrate();
        
        $result = $list->migrate(MigrationInterface::DIRECTION_DOWN);
        $this->assertCount(2, $result);

        /** @var \migration_2014_11_13_11_55|\migration_2014_11_13_12_05 $migration */
        foreach ($result as $migration) {
            $this->assertEquals('down', $migration->getExecuted());
        }
        
        $this->assertEquals(4, $this->adapter->getNumberOfMigrations());
    
        $result = $list->migrate(MigrationInterface::DIRECTION_DOWN);
        $this->assertCount(0, $result);
    }

    public function testBoth(): void
    {
        $list = $this->factory->newListFromDirectory(__DIR__.'/migrations');

        $result = $list->migrate();
        $this->assertCount(2, $result);
        $this->assertEquals(2, $this->adapter->getNumberOfMigrations());
    
        $result = $list->migrate(MigrationInterface::DIRECTION_DOWN);
        $this->assertCount(2, $result);
        $this->assertEquals(4, $this->adapter->getNumberOfMigrations());
    
        $list->migrate();
        $this->assertCount(2, $result);
        $this->assertEquals(6, $this->adapter->getNumberOfMigrations());
    }

    public function testNamespace(): void
    {
        $list = $this->factory->newListFromDirectory(__DIR__.'/namespaced', 'namespaced\\');

        $this->assertCount(1, $list);
        $result = $list->migrate();
        $this->assertCount(1, $result);
        $this->assertEquals(1, $this->adapter->getNumberOfMigrations());

        $this->expectException(InvalidArgumentException::class);
        $this->factory->newListFromDirectory(__DIR__.'/namespaced', 'namespaced');
    }
    
    public function testCallback(): void
    {
        $this->factory->setClassLoader(function ($classname) {
            return new $classname('inject here');
        });

        $list = $this->factory->newListFromDirectory(__DIR__.'/callback');

        $result = $list->migrate();
        /** @var \migration_with_callback $migration */
        foreach ($result as $migration) {
            $this->assertEquals('inject here', $migration->getSomeInjection());
        }
        
        $this->assertCount(1, $result);
    }

    public function testDifferentTableName(): void
    {
        $this->adapter->setTableName('other_table_name');

        $list = $this->factory->newListFromDirectory(__DIR__.'/migrations');
        $this->assertCount(2, $list);

        $result = $list->migrate();
        $this->assertCount(2, $result);
        $this->assertEquals(2, $this->adapter->getNumberOfMigrations());
    }
}
