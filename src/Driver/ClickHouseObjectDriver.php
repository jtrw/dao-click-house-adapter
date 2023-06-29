<?php

namespace Jtrw\DAO\Driver;

use Jtrw\DAO\DataAccessObjectInterface;
use RuntimeException;

class ClickHouseObjectDriver extends AbstractObjectDriver
{
    public function quoteTableName(string $name): string
    {
        return $name;
    }
    
    public function quoteColumnName(string $name): string
    {
        return $name;
    }
    
    public function getTableIndexes(DataAccessObjectInterface $object, string $tableName): array
    {
        // TODO: Implement getTableIndexes() method.
    }
    
    public function setForeignKeyChecks(DataAccessObjectInterface $object, bool $isEnable = true)
    {
        // TODO: Implement setForeignKeyChecks() method.
    }
    
    public function getTables(DataAccessObjectInterface $object): array
    {
        return $object->getCol("SHOW TABLES")->toNative();
    }
}
