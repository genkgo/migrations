<?php
use Genkgo\Migrations\AbstractMigration;

/**
 * Class migration_2014_11_13_11_55
 */
class migration_with_callback extends AbstractMigration
{
    private $someInjection;
    
    public function __construct($someInjection)
    {
        $this->someInjection = $someInjection;
    }

    /**
     *
     */
    public function up()
    {
    }

    /**
     *
     */
    public function down()
    {
    }
    
    public function getSomeInjection()
    {
        return $this->someInjection;
    }
}
