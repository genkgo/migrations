<?php
namespace Genkgo\Migrations\Utils;

use DirectoryIterator;
use IteratorAggregate;
use ArrayIterator;

class FileList implements IteratorAggregate
{
    /**
     * @var array
     */
    private $files = [];

    /**
     * @return ArrayIterator|\Traversable
     */

    public function getIterator()
    {
        return new ArrayIterator($this->files);
    }

    /**
     * @param $directory
     * @return FileList
     */
    
    public static function fromDirectory($directory)
    {
        $list = new static;
        
        $iterator = new DirectoryIterator($directory);
        foreach ($iterator as $file) {
            if ($file->isFile() && $file->getExtension() == 'php') {
                $list->files[] = $file->getPathname();
            }
        }
        
        return $list;
    }
}
