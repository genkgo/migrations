<?php

declare(strict_types=1);

namespace Genkgo\Migrations;

use Genkgo\Migrations\Utils\FileList;

final class Factory
{
    private \Closure $classLoader;

    public function __construct(private AdapterInterface $adapter, \Closure $classLoader = null)
    {
        $this->setClassLoader($classLoader);
    }

    public function setClassLoader(\Closure $classLoader = null): void
    {
        if (null === $classLoader) {
            $classLoader = fn ($classname) => new $classname;
        }

        $this->classLoader = $classLoader;
    }

    public function newList(string $namespace = '\\'): Collection
    {
        if (!\str_ends_with($namespace, '\\')) {
            throw new \InvalidArgumentException('Namespace incorrect, follow psr-4 namespace rules. Do not forget trailing slashes');
        }

        return (new Collection($this->adapter))->setNamespace($namespace);
    }

    public function newListFromDirectory(string $directory, string $namespace = '\\'): Collection
    {
        $collection = $this->newList($namespace);
        $classloader = $this->classLoader;

        $files = FileList::fromDirectory($directory);
        foreach ($files as $file) {
            require_once $file;
            $classname = \basename($file, '.php');
            $qualifiedClassName = $namespace . $classname;

            if (\is_a($qualifiedClassName, MigrationInterface::class, true)) {
                $collection->attach($classloader($qualifiedClassName));
            }
        }

        return $collection;
    }
}
