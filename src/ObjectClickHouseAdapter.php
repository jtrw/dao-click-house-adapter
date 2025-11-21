<?php

namespace Jtrw\DAO;

use ClickHouseDB\Client;
use Jtrw\DAO\Exceptions\DatabaseException;
use Jtrw\DAO\ValueObject\ArrayLiteral;
use Jtrw\DAO\ValueObject\StringLiteral;
use Jtrw\DAO\ValueObject\ValueObjectInterface;

class ObjectClickHouseAdapter extends ObjectAdapter
{
    /**
     * @var
     */
    protected Client $db;
    
    public function __construct(Client $db)
    {
        $this->db = $db;
        parent::__construct($db);
    } // end __construct
    
    public function quote(string $obj, int $type = 0): string
    {
        $escaped = str_replace(['\\', "'"], ['\\\\', "\\'"], $obj);
        return "'" . $escaped . "'";
    }
    
    public function getRow(string $sql): ValueObjectInterface
    {
        $result = $this->db->select($sql)->fetchOne();
    
        if (!$result) {
            $result = [];
        }
    
        return new ArrayLiteral($result);
    }
    
    public function getAll(string $sql): ValueObjectInterface
    {
        $result = $this->db->select($sql)->rows();
    
        if (!$result) {
            $result = [];
        }
    
        return new ArrayLiteral($result);
    }
    
    public function getCol(string $sql): ValueObjectInterface
    {
        $result = $this->db->select($sql)->fetchOne();
    
        if (!$result) {
            $result = [];
        }
    
        return new ArrayLiteral(array_values($result));
    }
    
    public function getOne(string $sql): StringLiteral
    {
        $result = $this->db->select($sql)->fetchOne();
        if (is_array($result) && !empty($result)) {
            $result = (string) reset($result);
        } else {
            $result = '';
        }
    
        return new StringLiteral($result);
    }
    
    public function getAssoc(string $sql): ValueObjectInterface
    {
        $rows = $this->db->select($sql)->rows();
        
        $result = [];
        
        foreach ($rows as $row) {
            $val = array_shift($row);
            if (count($row) === 1) {
                $row = array_shift($row);
            }
            $result[$val] = $row;
        }
        
        return new ArrayLiteral($result);
    }
    
    /**
     * ClickHouse does not support traditional ACID transactions.
     * This method is kept for interface compatibility but does nothing.
     *
     * @param bool $isolationLevel
     * @return void
     */
    public function begin(bool $isolationLevel = false)
    {
        // ClickHouse does not support transactions in the traditional sense
        // Operations are atomic at the block level
    }

    /**
     * ClickHouse does not support traditional ACID transactions.
     * This method is kept for interface compatibility but does nothing.
     *
     * @return void
     */
    public function commit()
    {
        // ClickHouse does not support transactions in the traditional sense
        // Operations are atomic at the block level
    }

    /**
     * ClickHouse does not support traditional ACID transactions.
     * This method is kept for interface compatibility but does nothing.
     *
     * @return void
     */
    public function rollback()
    {
        // ClickHouse does not support transactions in the traditional sense
        // Operations are atomic at the block level
    }
    
    public function query(string $sql): int
    {
        return (int) $this->db->write($sql)->countAll();
    }
    
    public function insert(string $table, array $values, bool $isUpdateDuplicate = false): int
    {
        $sql = $this->getInsertSQL($table, $values, $isUpdateDuplicate);
        
        return $this->query($sql);
    } // end insert
    
    public function massInsert(string $table, array $values, bool $inForeach = false)
    {
        return $this->db->insertAssocBulk($table, $values)->count();
    }
    
    /**
     * ClickHouse does not support auto-increment IDs in the traditional sense.
     * This method returns 0 for interface compatibility.
     *
     * Note: ClickHouse tables typically use UInt64 or other types for IDs,
     * and ID generation should be handled at the application level.
     *
     * @return int Always returns 0
     */
    public function getInsertID(): int
    {
        return 0;
    }
    
    /**
     * Update rows in ClickHouse using ALTER TABLE ... UPDATE mutation.
     *
     * Note: ClickHouse UPDATE is asynchronous and uses mutations.
     * The operation is not immediate and depends on merge operations.
     * For real-time updates, consider using ReplacingMergeTree or other strategies.
     *
     * @param string $table
     * @param array $values
     * @param array $condition
     * @return int Number of affected rows (always returns 0 for ClickHouse)
     */
    public function update(string $table, array $values, array $condition = []): int
    {
        $values = $this->getUpdateValues($values);

        $sql = "ALTER TABLE ".$table." UPDATE ".implode(", ", $values);

        if (is_array($condition)) {
            $sqlCondition = $this->getSqlCondition($condition);
            if ($sqlCondition) {
                $sql .= sprintf(static::SQL_WHERE, implode(static::SQL_AND, $sqlCondition));
            }
        } else {
            $sql .= sprintf(static::SQL_WHERE, $condition);
        }

        return $this->query($sql);
    }

    public function getDatabaseType(): string
    {
        return "ClickHouse";
    }
}
