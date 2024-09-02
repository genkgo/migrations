<?php

declare(strict_types=1);

namespace Genkgo\Migrations;

use Genkgo\Migrations\Utils\FileList;

final class Factory
{
    private ClassLoaderInterface $classLoader;

    public function __construct(private AdapterInterface $adapter, ClassLoaderInterface|\Closure $classLoader = null)
    {
        if ($classLoader instanceof ClassLoaderInterface) {
            $this->classLoader = $classLoader;
        } else {
            $this->setClassLoader($classLoader);
        }
    }

    public function setClassLoader(\Closure $classLoader = null): void
    {
        if (null === $classLoader) {
            $classLoader = fn ($classname) => new $classname;
        }

        $this->classLoader = new CallbackClassLoader($classLoader);
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

            $collection->attach($this->classLoader->newInstance($qualifiedClassName));
        }

        return $collection;
    }
}
