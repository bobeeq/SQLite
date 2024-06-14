<?php

namespace bobeeq\sqlite;

class SQLite
{
    private static string $dbPath = "";

    private static ?\PDO $connection = null;

    public static function init(string $dbPath) {
        static::$dbPath = $dbPath;
    }

    public static function conn(): \PDO
    {
        if(empty(static::$dbPath)) {
            throw new DbPathNotProvidedException("There is no \$dbPath declared. Use SQLite::init() to provide.");
        }
        
        if (empty(static::$connection)) {
            static::$connection = new \PDO("sqlite:" . static::$dbPath);
        }

        return static::$connection;
    }

    public static function qry(string $query)
    {
        static::conn()->beginTransaction();
        $results = static::conn()->query($query);
        static::conn()->commit();

        return $results;
    }

    public static function select(string $query): array
    {
        return static::qry($query)->fetchAll(\PDO::FETCH_ASSOC);
    }
}