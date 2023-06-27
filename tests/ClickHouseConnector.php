<?php

namespace Jtrw\DAO\Tests;

use ClickHouseDB\Client;
use Jtrw\DAO\DataAccessObjectInterface;
use Jtrw\DAO\ObjectClickHouseAdapter;
use RuntimeException;

class ClickHouseConnector
{
    public const DRIVER_NAME = "clickhouse";
    
    /**
     * @var DataAccessObjectInterface
     */
    public static DataAccessObjectInterface $db;
    
    private static Client $sourceConnection;
    
    
    public static function init()
    {
        static::$db = self::initClickHouse();
    }
    
    private static function initClickHouse(): DataAccessObjectInterface
    {
       // $dbName = getenv('MYSQL_DATABASE');
       // $dsn = "mysql:dbname=dao;port=3306;host=dao_mariadb";
    
        $config = [
            'host' => 'clickhouse',
            'port' => '8123',
            'username' => 'default',
            'password' => 'password123'
        ];
        $db = new Client($config);
    
        static::$sourceConnection = $db;
        
        return new ObjectClickHouseAdapter($db);
    }
    
    public static function getInstance(): DataAccessObjectInterface
    {
        if (null !== static::$db) {
            return static::$db;
        }
        
        throw new RuntimeException("Driver Not be Initialized");
    }
    
    public static function getSourceConnection(): Client
    {
        if (null !== static::$sourceConnection) {
            return static::$sourceConnection;
        }
    
        throw new RuntimeException("Conncetion Not be Initialized");
    }
}
