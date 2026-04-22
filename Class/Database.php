<?php
require_once __DIR__ . './../vendor/autoload.php';

$dotenv = Dotenv\Dotenv::createImmutable(__DIR__ . '/../');
$dotenv->load();

class Database
{

    public $conn;

    public function connect()
    {
        $this->conn = new mysqli(
            $_ENV['host'],
            $_ENV['username'],
            $_ENV['password'],
            $_ENV['dbname'],
            $_ENV['port']
        );

        if ($this->conn->connect_error) {
            die("Database Connection Failed: " . $this->conn->connect_error);
        }

        return $this->conn;
    }
}
