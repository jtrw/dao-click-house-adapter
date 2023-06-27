<?php

namespace Jtrw\DAO;

use ClickHouseDB\Client;
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
        return $obj;
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
        // TODO: Implement getCol() method.
    }
    
    public function getOne(string $sql): StringLiteral
    {
        $result = $this->db->select($sql)->fetchOne() ?? '';
    
        return new StringLiteral($result);
    }
    
    public function getAssoc(string $sql): ValueObjectInterface
    {
        // TODO: Implement getAssoc() method.
    }
    
    public function begin(bool $isolationLevel = false)
    {
        // TODO: Implement begin() method.
    }
    
    public function commit()
    {
        // TODO: Implement commit() method.
    }
    
    public function rollback()
    {
        // TODO: Implement rollback() method.
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
    
    public function getInsertID(): int
    {
        // TODO: Implement getInsertID() method.
    }
    
    public function getDatabaseType(): string
    {
        return "clickhouse";
    }
}
