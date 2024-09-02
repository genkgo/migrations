<?php

declare(strict_types=1);

namespace Genkgo\Migrations;

final class CallbackClassLoader implements ClassLoaderInterface
{
    public function __construct(private readonly \Closure $closure)
    {
    }

    public function newInstance(string $className): MigrationInterface
    {
        $object = \call_user_func($this->closure, $className);
        if ($object instanceof MigrationInterface === false) {
            throw new \RuntimeException(\sprintf('%s is not an implementation of MigrationInterface.', $object::class));
        }

        return $object;
    }
}
