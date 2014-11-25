<?php
namespace namespaced;

use Genkgo\Migrations\AbstractMigration;

/**
 * Class migration_2014_11_13_12_05
 * @package namespaced
 */
class migration_2014_11_13_12_05 extends AbstractMigration
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
