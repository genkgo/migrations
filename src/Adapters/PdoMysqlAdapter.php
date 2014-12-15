<?php
namespace Genkgo\Migrations\Adapters;

/**
 * Class PdoMysqlAdapter
 * @package Genkgo\Migrations\Adapters
 */
class PdoMysqlAdapter extends AbstractPdoAdapter
{
    /**
     *
     */
    protected function createTableIfNotExists()
    {
        $this->getPdo()->exec(
            
"CREATE TABLE IF NOT EXISTS `{$this->table}` (
  `id` INT(11) UNSIGNED NOT NULL AUTO_INCREMENT,
  `name` VARCHAR(255) NOT NULL,
  `migration` VARCHAR(255) NOT NULL,
  `direction` TINYINT(1) UNSIGNED NOT NULL,
  `migrated_on` DATETIME NOT NULL,
  PRIMARY KEY (`id`))"
            
        );
    }
}
