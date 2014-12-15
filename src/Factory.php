<?php
namespace Genkgo\Migrations;

use Closure;
use Genkgo\Migrations\Utils\FileList;

/**
 * Class Factory
 * @package Genkgo\Migrations
 */
class Factory
{
    /**
     *
     * @var AdapterInterface
     */
    
    private $adapter;

    /**
     * @var Closure
     */

    private $classLoader;
    
    /**
     *
     * @param AdapterInterface $adapter
     * @param Closure $classLoader
     */
    
    public function __construct(AdapterInterface $adapter, Closure $classLoader = null)
    {
        $this->adapter = $adapter;
        $this->setClassLoader($classLoader);
    }

    /**
     * @param callable $classLoader
     */

    public function setClassLoader(Closure $classLoader = null)
    {
        if (null === $classLoader) {
            $classLoader = function ($classname) {
                return new $classname;
            };
        }
        $this->classLoader = $classLoader;
    }

    /**
     * @param string $namespace
     * @return Collection
     */

    public function newList($namespace = '\\')
    {
        if (substr($namespace, -2) !== '\\') {
            throw new \InvalidArgumentException('Namespace incorrect, follow psr-4 namespacing. Dont forget trailing slashes');
        }
        return (new Collection($this->adapter))->setNamespace($namespace);
    }

    /**
     * @param string $directory
     * @param string $namespace
     * @return Collection
     */

    public function newListFromDirectory($directory, $namespace = '\\')
    {
        $collection = $this->newList($namespace);
        $classloader = $this->classLoader;

        $files = FileList::fromDirectory($directory);
        foreach ($files as $file) {
            require_once $file;
            $classname = basename($file, '.php');
            $fullname = $namespace . $classname;

            if (is_a($fullname, MigrationInterface::class, true)) {
                $collection->attach($classloader($fullname));
            }
        }

        return $collection;
    }
}
