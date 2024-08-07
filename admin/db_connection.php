<?php



// db_connection.php
// $config = require 'config.php';
// $dsn = "mysql:host={$config['database']['host']};dbname={$config['database']['dbname']};charset=utf8mb4";
// $options = [
//     PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
//     PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
//     PDO::ATTR_EMULATE_PREPARES => false,
// ];
// try {
//     $pdo = new PDO($dsn, $config['database']['user'], $config['database']['password'], $options);
// } catch (\PDOException $e) {
//     throw new \PDOException($e->getMessage(), (int)$e->getCode());
// }
// config.php
return [
    'database' => [
        'host' => 'localhost',
        'dbname' => 'ki_db',
        'user' => 'root',
        'password' => '',
    ],
];

// db_connection.php
class Database {
    private static $pdo = null;

    public static function getConnection() {
        if (self::$pdo === null) {
            $config = require 'config.php';
            $dsn = "mysql:host={$config['database']['host']};dbname={$config['database']['dbname']};charset=utf8mb4";
            $options = [
                PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
                PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
                PDO::ATTR_EMULATE_PREPARES => false,
            ];
            try {
                self::$pdo = new PDO($dsn, $config['database']['user'], $config['database']['password'], $options);
            } catch (\PDOException $e) {
                error_log("Database Connection Error: " . $e->getMessage());
                throw new \PDOException("Database Connection Failed", (int)$e->getCode());
            }
        }
        return self::$pdo;
    }
}