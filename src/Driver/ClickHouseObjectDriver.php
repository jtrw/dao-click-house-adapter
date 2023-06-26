<?php

namespace Jtrw\DAO\Driver;

use Jtrw\DAO\DataAccessObjectInterface;
use RuntimeException;

class ClickHouseObjectDriver implements ObjectDriverInterface
{
    
    public function quoteTableName(string $name): string
    {
        return $name;
    }
    
    public function quoteColumnName(string $name): string
    {
        return $name;
    }
    
    public function createSelectQuery(array $columns, string $from, ?array $joins = null, ?array $where = null, ?array $orderBy = null, ?int $limit = null, ?int $offset = null, ?array $groupBy = null, ?array $having = null): string
    {
        throw new RuntimeException("Method is not supported");
    }
    
    public function getSplitOnPages(DataAccessObjectInterface $object, string $query, int $col, int $page): array
    {
        throw new RuntimeException("Method is not supported");
    }
    
    public function getTableIndexes(DataAccessObjectInterface $object, string $tableName): array
    {
        throw new RuntimeException("Method is not supported");
    }
    
    public function setForeignKeyChecks(DataAccessObjectInterface $object, bool $isEnable = true)
    {
        throw new RuntimeException("Method is not supported");
    }
    
    public function getTables(DataAccessObjectInterface $object): array
    {
        throw new RuntimeException("Method is not supported");
    }
}
