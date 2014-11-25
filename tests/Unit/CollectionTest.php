<?php
namespace Genkgo\Migrations\Unit;

use PDO;
use Genkgo\Migrations\AbstractTestCase;
use Genkgo\Migrations\Collection;
use Genkgo\Migrations\Adapters\PdoSqliteAdapter;

/**
 * Class MigrateTest
 * @package Genkgo\Migrations
 */
class CollectionTest extends AbstractTestCase
{
    /**
     * @var Collection
     */
    private $collection;
    
    protected function setUp()
    {
        $this->collection = new Collection(
            new PdoSqliteAdapter(new PDO('sqlite::memory:'))
        );
    }
    
    public function testAttach()
    {
        $this->collection->attach(new TestMigration());
        $this->assertCount(1, $this->collection);
    }

    public function testDetach()
    {
        $script = new TestMigration();
        $this->collection->attach($script);
        $this->assertCount(1, $this->collection);
        $this->collection->detach($script);
        $this->assertCount(0, $this->collection);

        $this->setExpectedException(\InvalidArgumentException::class);
        $this->collection->detach(new TestMigration());
    }
}
