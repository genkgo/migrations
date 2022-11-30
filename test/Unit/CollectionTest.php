<?php

declare(strict_types=1);

namespace Genkgo\TestMigrations\Unit;

use Genkgo\TestMigrations\AbstractTestCase;
use PDO;
use Genkgo\Migrations\Collection;
use Genkgo\Migrations\Adapters\PdoSqliteAdapter;

final class CollectionTest extends AbstractTestCase
{
    private Collection $collection;

    protected function setUp(): void
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

        $this->expectException(\InvalidArgumentException::class);
        $this->collection->detach(new TestMigration());
    }
}
