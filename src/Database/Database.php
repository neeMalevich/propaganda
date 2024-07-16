<?php
namespace App\Database;

use Exception;
use PDO;
use PDOException;

final class Database
{
    public static $instance = null;
    public $connection;
    public $dsn;

    public function __construct()
    {
        $this->dsn = "mysql:host=127.0.0.1;dbname=propaganda;charset=utf8mb4";

        try {
            $this->connection = new PDO($this->dsn, "root", "root");
            $this->connection->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
        } catch (PDOException $e) {
            throw new Exception("Database connection error: " . $e->getMessage());
        }
    }

    public static function getInstance(): Database
    {
        if (self::$instance === null) {
            self::$instance = new Database();
        }
        return self::$instance;
    }

    public function getConnection(): PDO
    {
        return $this->connection;
    }

    public function closeConnection(): void
    {
        $this->connection = null;
    }
}