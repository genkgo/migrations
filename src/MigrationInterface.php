<?php
namespace Genkgo\Migrations;

interface MigrationInterface
{
    const DIRECTION_UP = 1;

    const DIRECTION_DOWN = 2;

    /**
     * @return void
     */
    
    public function up();


    /**
     * @return void
     */
    public function down();
    
    /**
     * @return string
     */
    public function getName();
}
