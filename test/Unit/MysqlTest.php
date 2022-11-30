<?php

declare(strict_types=1);

namespace Genkgo\TestMigrations\Unit;

use Genkgo\Migrations\AdapterInterface;
use Genkgo\Migrations\Adapters\PdoMysqlAdapter;
use Genkgo\TestMigrations\AbstractTestCase;
use PDO;
use PHPUnit\Framework\MockObject\MockObject;

final class MysqlTest extends AbstractTestCase
{
    private AdapterInterface $adapter;

    /**
     * @var PDO&MockObject
     */
    private PDO $pdo;

    public function setUp(): void
    {
        $this->pdo = $this->createMock(PDO::class);
        $this->adapter = new PdoMysqlAdapter($this->pdo);
    }

    public function testCreateTable(): void
    {
        $this->pdo->expects($this->once())
            ->method('exec')
            ->with($this->equalTo(
                "CREATE TABLE IF NOT EXISTS `migrations` (
  `id` INT(11) UNSIGNED NOT NULL AUTO_INCREMENT,
  `name` VARCHAR(255) NOT NULL,
  `migration` VARCHAR(255) NOT NULL,
  `direction` TINYINT(1) UNSIGNED NOT NULL,
  `migrated_on` DATETIME NOT NULL,
  PRIMARY KEY (`id`))"
            ));

        $this->adapter->setup();
    }
}
