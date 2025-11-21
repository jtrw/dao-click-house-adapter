<?php

namespace Jtrw\DAO\Driver;

use Jtrw\DAO\DataAccessObjectInterface;
use RuntimeException;

class ClickHouseObjectDriver extends AbstractObjectDriver
{
    /**
     * Quote table name for ClickHouse.
     * ClickHouse uses backticks for identifiers with special characters.
     *
     * @param string $name
     * @return string
     */
    public function quoteTableName(string $name): string
    {
        // Handle database.table notation
        if (strpos($name, '.') !== false) {
            $parts = explode('.', $name, 2);
            return '`' . $parts[0] . '`.`' . $parts[1] . '`';
        }

        return '`' . $name . '`';
    }

    /**
     * Quote column name for ClickHouse.
     * ClickHouse uses backticks for identifiers with special characters.
     *
     * @param string $name
     * @return string
     */
    public function quoteColumnName(string $name): string
    {
        $name = '`' . $name . '`';

        // Handle table.column notation
        if (strpos($name, '.') !== false) {
            $name = str_replace('.', '`.`', $name);
            $keys = explode('.', $name);

            // Handle wildcard selector (table.*)
            if (isset($keys[1]) && $keys[1] === '`*`') {
                $keys[1] = trim($keys[1], '`');
                $name = implode('.', $keys);
            }
        }

        return $name;
    }
    
    /**
     * Get table indexes from ClickHouse system tables.
     * Returns information about primary keys and sorting keys.
     *
     * @param DataAccessObjectInterface $object
     * @param string $tableName
     * @return array
     */
    public function getTableIndexes(DataAccessObjectInterface $object, string $tableName): array
    {
        $sql = "SELECT
                    name,
                    engine,
                    primary_key,
                    sorting_key,
                    partition_key
                FROM system.tables
                WHERE name = " . $object->quote($tableName) . "
                AND database = currentDatabase()";

        $result = $object->getAll($sql)->toNative();

        // Also get data skipping indexes
        $skipIndexSql = "SELECT
                            name,
                            type,
                            expr
                         FROM system.data_skipping_indices
                         WHERE table = " . $object->quote($tableName) . "
                         AND database = currentDatabase()";

        $skipIndexes = $object->getAll($skipIndexSql)->toNative();

        return [
            'table_info' => $result,
            'data_skipping_indexes' => $skipIndexes
        ];
    }

    /**
     * ClickHouse does not support foreign key constraints.
     * This method is kept for interface compatibility but does nothing.
     *
     * @param DataAccessObjectInterface $object
     * @param bool $isEnable
     * @return void
     */
    public function setForeignKeyChecks(DataAccessObjectInterface $object, bool $isEnable = true)
    {
        // ClickHouse does not support foreign key constraints
        // This method does nothing but maintains interface compatibility
    }

    /**
     * Get list of tables in the current database.
     *
     * @param DataAccessObjectInterface $object
     * @return array
     */
    public function getTables(DataAccessObjectInterface $object): array
    {
        return $object->getCol("SHOW TABLES")->toNative();
    }
}
