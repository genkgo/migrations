<?php

declare(strict_types=1);

namespace Genkgo\Migrations\Utils;

/**
 * @implements \IteratorAggregate<string>
 */
final class FileList implements \IteratorAggregate
{
    /**
     * @var array<int, string>
     */
    private array $files = [];

    /**
     * @return \ArrayIterator<int, string>
     */
    public function getIterator(): \ArrayIterator
    {
        return new \ArrayIterator($this->files);
    }

    public static function fromDirectory(string $directory): self
    {
        $list = new self;
        
        $iterator = new \DirectoryIterator($directory);
        foreach ($iterator as $file) {
            if ($file->isFile() && $file->getExtension() == 'php') {
                $list->files[] = $file->getPathname();
            }
        }
        
        return $list;
    }
}
