<?php
namespace Genkgo\Migrations\Adapters;

use PDO;
use Genkgo\Migrations\AdapterInterface;
use Genkgo\Migrations\MigrationInterface;
use Genkgo\Migrations\AlreadyMigratedException;

/**
 * Class AbstractPdoAdapter
 * @package Genkgo\Migrations\Adapters
 */
abstract class AbstractPdoAdapter implements AdapterInterface
{
    /**
     * @var PDO
     */
    private $pdo;

    /**
     * @var string
     */
    private $table = 'migrations';

    /**
     * @param PDO $pdo
     */
    public function __construct(PDO $pdo)
    {
        $this->pdo = $pdo;
    }

    /**
     * @return void
     */
    abstract protected function createTableIfNotExists();


    /**
     * @param $namespace
     * @param MigrationInterface $migration
     */
    public function upgrade($namespace, MigrationInterface $migration)
    {
        $this->createTableIfNotExists();
        $this->verifyNotMigrated($namespace, $migration, MigrationInterface::DIRECTION_UP);
        $migration->up();
        $this->log($namespace, $migration, MigrationInterface::DIRECTION_UP);
    }

    /**
     * @param $namespace
     * @param MigrationInterface $migration
     */
    public function downgrade($namespace, MigrationInterface $migration)
    {
        $this->createTableIfNotExists();
        $this->verifyNotMigrated($namespace, $migration, MigrationInterface::DIRECTION_DOWN);
        $migration->down();
        $this->log($namespace, $migration, MigrationInterface::DIRECTION_DOWN);
    }

    /**
     * @return PDO
     */
    protected function getPdo()
    {
        return $this->pdo;
    }


    /**
     * @param $namespace
     * @param MigrationInterface $migration
     * @param $direction
     */
    private function verifyNotMigrated($namespace, MigrationInterface $migration, $direction)
    {
        $query = "SELECT * FROM {$this->table} WHERE name = ? AND migration = ? ORDER BY migrated_on DESC, id DESC LIMIT 1";
    
        $statement = $this->pdo->prepare($query);
        $statement->execute([
            $namespace,
            $migration->getName(),
        ]);
    
        $result = $statement->fetch(PDO::FETCH_ASSOC);
        if ($result && $result['direction'] == $direction) {
            throw new AlreadyMigratedException();
        }
    }

    /**
     * @param $namespace
     * @param MigrationInterface $migration
     * @param $direction
     */
    private function log($namespace, MigrationInterface $migration, $direction)
    {
        $statement = $this->pdo->prepare("INSERT INTO {$this->table} (name, migration, direction, migrated_on) VALUES (?, ?, ?, ?)");
        $statement->execute([
            $namespace,
            $migration->getName(),
            $direction,
            date('Y-m-d H:i:s')
        ]);
    }

    /**
     * @return int
     */
    public function getNumberOfMigrations()
    {
        $query = "SELECT id FROM {$this->table}";
    
        $statement = $this->pdo->prepare($query);
        $statement->execute();
        return count($statement->fetchAll());
    }
    
    /**
     *
     */
    
    public function setup()
    {
        $this->createTableIfNotExists();
    }
}
