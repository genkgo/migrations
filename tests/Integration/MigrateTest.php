<?php
namespace Genkgo\Migrations\Integration;

use PDO;
use Genkgo\Migrations\Adapters\PdoSqliteAdapter;
use Genkgo\Migrations\Utils\FileList;
use Genkgo\Migrations\AbstractTestCase;
use Genkgo\Migrations\Factory;
use Genkgo\Migrations\MigrationInterface;

/**
 * Class MigrateTest
 * @package Genkgo\Migrations
 */
class MigrateTest extends AbstractTestCase
{
    /**
     * @var AdapterInterface
     */
    private $adapter;

    /**
     * @var Factory
     */
    private $factory;

    /**
     *
     */
    public function setUp()
    {
        $pdo = new PDO('sqlite::memory:');
        $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
        
        $adapter = new PdoSqliteAdapter($pdo);
        $this->factory = new Factory($adapter);
        $this->adapter = $adapter;
    }

    /**
     *
     */
    public function testUpgrade()
    {
        $list = $this->factory->newListFromDirectory(__DIR__.'/migrations');
        $this->assertCount(2, $list);
        
        $result = $list->migrate();
        $this->assertCount(2, $result);
        $this->assertEquals(2, $this->adapter->getNumberOfMigrations());
        
        foreach ($result as $migration) {
            $this->assertEquals('up', $migration->getExecuted());
        }
        
        $result = $list->migrate();
        $this->assertCount(0, $result);
    }

    /**
     *
     */
    public function testDowngrade()
    {
        $list = $this->factory->newListFromDirectory(__DIR__.'/migrations');
        $list->migrate();
        
        $result = $list->migrate(MigrationInterface::DIRECTION_DOWN);
        $this->assertCount(2, $result);
        
        foreach ($result as $migration) {
            $this->assertEquals('down', $migration->getExecuted());
        }
        
        $this->assertEquals(4, $this->adapter->getNumberOfMigrations());
    
        $result = $list->migrate(MigrationInterface::DIRECTION_DOWN);
        $this->assertCount(0, $result);
    }

    /**
     *
     */
    public function testBoth()
    {
        $list = $this->factory->newListFromDirectory(__DIR__.'/migrations');

        $result = $list->migrate();
        $this->assertCount(2, $result);
        $this->assertEquals(2, $this->adapter->getNumberOfMigrations());
    
        $result = $list->migrate(MigrationInterface::DIRECTION_DOWN);
        $this->assertCount(2, $result);
        $this->assertEquals(4, $this->adapter->getNumberOfMigrations());
    
        $list->migrate();
        $result = $this->assertCount(2, $result);
        $this->assertEquals(6, $this->adapter->getNumberOfMigrations());
    }

    /**
     *
     */
    public function testNamespace()
    {
        $list = $this->factory->newListFromDirectory(__DIR__.'/namespaced', 'namespaced\\');

        $this->assertCount(1, $list);
        $result = $list->migrate();
        $this->assertCount(1, $result);
        $this->assertEquals(1, $this->adapter->getNumberOfMigrations());
    }
    
    /**
     *
     */
    public function testCallback()
    {
        $this->factory->setClassLoader(function ($classname) {
            return new $classname('inject here');
        });

        $list = $this->factory->newListFromDirectory(__DIR__.'/callback');

        $result = $list->migrate();
        foreach ($result as $migration) {
            $this->assertEquals('inject here', $migration->getSomeInjection());
        }
        
        $this->assertCount(1, $result);
    }
}
