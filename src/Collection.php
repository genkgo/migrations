<?php
namespace Genkgo\Migrations;

use Countable;
use InvalidArgumentException;

/**
 * Class Collection
 * @package Genkgo\Migrations
 */
class Collection implements Countable
{
    /**
     *
     * @var AdapterInterface
     */
    private $adapter;
    
    /**
     *
     * @var array
     */
    private $list = [];
    
    /**
     *
     * @var string
     */
    private $namespace;
    
    /**
     *
     * @param AdapterInterface $adapter
     */
    
    public function __construct(AdapterInterface $adapter)
    {
        $this->adapter = $adapter;
    }
    
    /**
     *
     * @param MigrationInterface $migration
     */
    
    public function attach(MigrationInterface $migration)
    {
        $this->list[] = $migration;
    }
    
    /**
     *
     * @param MigrationInterface $migration
     */
    
    public function detach(MigrationInterface $migration)
    {
        if (($key = array_search($migration, $this->list)) !== false) {
            unset($this->list[$key]);
        } else {
            throw new InvalidArgumentException('Migration not in collection');
        }
    }

    /**
     * @param $namespace
     * @return $this
     */

    public function setNamespace($namespace)
    {
        $this->namespace = $namespace;
        return $this;
    }

    /**
     * @return string
     */

    public function getNamespace()
    {
        return $this->namespace;
    }

    /**
     * @param int $direction
     * @return Log
     */
    
    public function migrate($direction = MigrationInterface::DIRECTION_UP)
    {
        $result = new Log();
        
        usort($this->list, function ($item1, $item2) {
            $class1 = $item1->getName();
            $class2 = $item2->getName();
            return strcmp($class1, $class2);
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

    /**
     * @param MigrationInterface $migration
     * @param $direction
     */
    
    private function execute(MigrationInterface $migration, $direction)
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

    /**
     * @return int
     */
    
    public function count()
    {
        return count($this->list);
    }
}
