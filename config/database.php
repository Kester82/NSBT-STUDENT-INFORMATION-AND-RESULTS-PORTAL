<?php
declare(strict_types=1);

class Database
{
    public static function connect(): PDO
    {
        static $connection = null;

        if ($connection instanceof PDO) {
            return $connection;
        }

        $host = '127.0.0.1';
        $databaseName = 'nsbt_portal';
        $username = 'root';
        $password = '';
        $charset = 'utf8mb4';

        $dsn = "mysql:host={$host};dbname={$databaseName};charset={$charset}";

        $options = [
            PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
            PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
            PDO::ATTR_EMULATE_PREPARES => false,
        ];

        try {
            $connection = new PDO($dsn, $username, $password, $options);

            return $connection;
        } catch (PDOException $exception) {
            error_log('Database connection failed: ' . $exception->getMessage());

            http_response_code(500);
            exit('Unable to connect to the database. Please try again later.');
        }
    }
}