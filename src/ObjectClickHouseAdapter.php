<?php

namespace Jtrw\DAO;

use ClickHouseDB\Client;
use Jtrw\DAO\ValueObject\ValueObjectInterface;

class ObjectClickHouseAdapter extends ObjectAdapter
{
    /**
     * @var
     */
    protected Client $db;
    
    public function __construct(Client $db)
    {
        parent::__construct($db);
    } // end __construct
    
    public function quote(string $obj, int $type = 0): string
    {
        // TODO: Implement quote() method.
    }
    
    public function getRow(string $sql): ValueObjectInterface
    {
        // TODO: Implement getRow() method.
    }
    
    public function getAll(string $sql): ValueObjectInterface
    {
        // TODO: Implement getAll() method.
    }
    
    public function getCol(string $sql): ValueObjectInterface
    {
        // TODO: Implement getCol() method.
    }
    
    public function getOne(string $sql): ValueObjectInterface
    {
        // TODO: Implement getOne() method.
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
        // TODO: Implement query() method.
    }
    
    public function getInsertID(): int
    {
        // TODO: Implement getInsertID() method.
    }
    
    public function getDatabaseType(): string
    {
        // TODO: Implement getDatabaseType() method.
    }
}
