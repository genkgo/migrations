<?php

declare(strict_types=1);

namespace Genkgo\Migrations\Adapters;

use Genkgo\Migrations\AdapterInterface;
use Genkgo\Migrations\MigrationInterface;
use Genkgo\Migrations\AlreadyMigratedException;

abstract class AbstractPdoAdapter implements AdapterInterface
{
    protected string $table = 'migrations';

    public function __construct(private \PDO $pdo)
    {
    }

    public function setTableName(string $tableName): void
    {
        $this->table = $tableName;
    }

    abstract protected function createTableIfNotExists(): void;


    public function upgrade(string $namespace, MigrationInterface $migration): void
    {
        $this->createTableIfNotExists();
        $this->verifyNotMigrated($namespace, $migration, MigrationInterface::DIRECTION_UP);
        $migration->up();
        $this->log($namespace, $migration, MigrationInterface::DIRECTION_UP);
    }

    public function downgrade(string $namespace, MigrationInterface $migration): void
    {
        $this->createTableIfNotExists();
        $this->verifyNotMigrated($namespace, $migration, MigrationInterface::DIRECTION_DOWN);
        $migration->down();
        $this->log($namespace, $migration, MigrationInterface::DIRECTION_DOWN);
    }

    protected function getPdo(): \PDO
    {
        return $this->pdo;
    }

    private function verifyNotMigrated(string $namespace, MigrationInterface $migration, int $direction): void
    {
        $query = "SELECT * FROM {$this->table} WHERE name = ? AND migration = ? ORDER BY migrated_on DESC, id DESC LIMIT 1";
    
        $statement = $this->pdo->prepare($query);
        $statement->execute([
            $namespace,
            $migration->getName(),
        ]);

        /** @var false|array{name: string, migration: string, direction: int, migrated_on: string} $result */
        $result = $statement->fetch(\PDO::FETCH_ASSOC);
        if ($result && $result['direction'] == $direction) {
            throw new AlreadyMigratedException();
        }
    }

    private function log(string $namespace, MigrationInterface $migration, int $direction): void
    {
        $statement = $this->pdo->prepare("INSERT INTO {$this->table} (name, migration, direction, migrated_on) VALUES (?, ?, ?, ?)");
        $statement->execute([
            $namespace,
            $migration->getName(),
            $direction,
            \date('Y-m-d H:i:s')
        ]);
    }

    public function getNumberOfMigrations(): int
    {
        $query = "SELECT id FROM {$this->table}";
    
        $statement = $this->pdo->prepare($query);
        $statement->execute();
        return \count($statement->fetchAll());
    }
    
    public function setup(): void
    {
        $this->createTableIfNotExists();
    }
}
