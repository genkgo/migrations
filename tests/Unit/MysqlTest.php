<?php
namespace Genkgo\Migrations\Unit;

use Genkgo\Migrations\Adapters\PdoMysqlAdapter;
use Genkgo\Migrations\Utils\FileList;
use Genkgo\Migrations\Unit\PDOMock;
use Genkgo\Migrations\AbstractTestCase;

/**
 * Class MigrateTest
 * @package Genkgo\Migrations
 */
class MysqlTest extends AbstractTestCase
{
    /**
     * @var AdapterInterface
     */
    private $adapter;
    
    /**
     *
     * @var PDOMock
     */
    
    private $pdo;

    /**
     *
     */
    public function setUp()
    {
        $this->pdo = $this->getMockBuilder(PDOMock::class)->getMock(['exec']);
        $this->adapter = new PdoMysqlAdapter($this->pdo);
    }

    /**
     *
     */
    public function testCreateTable()
    {
        $this->pdo->expects($this->once())->method('exec')->with($this->equalTo(
"CREATE TABLE IF NOT EXISTS `migrations` (
  `id` INT(11) UNSIGNED NOT NULL AUTO_INCREMENT,
  `name` VARCHAR(255) NOT NULL,
  `migration` VARCHAR(255) NOT NULL,
  `direction` TINYINT(1) UNSIGNED NOT NULL,
  `migrated_on` DATETIME NOT NULL,
  PRIMARY KEY (`id`))"));
        $this->adapter->setup();
    }
}
