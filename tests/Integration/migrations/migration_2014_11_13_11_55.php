<?php
use Genkgo\Migrations\AbstractMigration;

/**
 * Class migration_2014_11_13_11_55
 */
class migration_2014_11_13_11_55 extends AbstractMigration
{
    /**
     * @var
     */
    private $executed;

    /**
     *
     */
    public function up()
    {
        $this->executed = 'up';
    }

    /**
     *
     */
    public function down()
    {
        $this->executed = 'down';
    }

    /**
     * @return mixed
     */
    public function getExecuted()
    {
        return $this->executed;
    }
}
