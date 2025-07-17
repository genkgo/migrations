<?php

declare(strict_types=1);

namespace Genkgo\Migrations;

final readonly class CallbackClassLoader implements ClassLoaderInterface
{
    public function __construct(private \Closure $closure)
    {
    }

    public function newInstance(string $className): MigrationInterface
    {
        /** @var object $object */
        $object = \call_user_func($this->closure, $className);
        if ($object instanceof MigrationInterface === false) {
            throw new \RuntimeException(\sprintf('%s is not an implementation of MigrationInterface.', $object::class));
        }

        return $object;
    }
}
