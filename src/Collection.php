<?php

declare(strict_types=1);

namespace Genkgo\Migrations;

final class Collection implements \Countable
{
    /**
     * @var array<int, MigrationInterface>
     */
    private array $list = [];
    private string $namespace = '';
    
    public function __construct(private AdapterInterface $adapter)
    {
    }
    
    public function attach(MigrationInterface $migration): void
    {
        $this->list[] = $migration;
    }
    
    public function detach(MigrationInterface $migration): void
    {
        if (($key = \array_search($migration, $this->list, true)) !== false) {
            unset($this->list[$key]);
            return;
        }

        throw new \InvalidArgumentException('Migration not in collection');
    }

    public function setNamespace(string $namespace): self
    {
        $this->namespace = $namespace;
        return $this;
    }

    public function getNamespace(): string
    {
        return $this->namespace;
    }

    public function migrate(int $direction = MigrationInterface::DIRECTION_UP): Log
    {
        $result = new Log();
        
        \usort($this->list, function ($item1, $item2) {
            $class1 = $item1->getName();
            $class2 = $item2->getName();
            return \strcmp($class1, $class2);
        });
        
        foreach ($this->list as $item) {
            try {
                $this->execute($item, $direction);
                $result->attach($item);
            } catch (AlreadyMigratedException $e) {
                /** we will not execute migrations that are already executed */
            } catch (NotReadyToMigrateException $e) {
                /** we will not execute migrations that are not ready to be executed */
            }
        }
        
        return $result;
    }

    private function execute(MigrationInterface $migration, int $direction): void
    {
        if ($direction == MigrationInterface::DIRECTION_UP) {
            $this->adapter->upgrade(
                $this->getNamespace(),
                $migration
            );
        } elseif ($direction == MigrationInterface::DIRECTION_DOWN) {
            $this->adapter->downgrade(
                $this->getNamespace(),
                $migration
            );
        }
    }

    public function count(): int
    {
        return \count($this->list);
    }
}
