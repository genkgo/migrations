<?php
namespace Genkgo\Migrations;

use Verraes\ClassFunctions\ClassFunctions;

abstract class AbstractMigration implements MigrationInterface
{
    /**
     * @return void
     */
    
    abstract public function up();


    /**
     * @return void
     */
    abstract public function down();
    
    /**
     * @return string
     */
    
    public function getName()
    {
        return ClassFunctions::short($this);
    }
}
