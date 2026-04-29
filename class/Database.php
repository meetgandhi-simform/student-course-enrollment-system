<?php
require_once __DIR__ . './../vendor/autoload.php';

$dotenv = Dotenv\Dotenv::createImmutable(__DIR__ . '/../');
$dotenv->load();

/**
 * Class Database
 * 
 * Handles database connection using environment variables.
 */
class Database
{
    /**
     * @var mysqli Database connection instance
     */
    public $conn;

    /**
     * Establish database connection
     *
     * Uses environment variables loaded via Dotenv to connect to MySQL.
     *
     * @return mysqli Returns active database connection
     * 
     * @throws Exception If connection fails
     */
    public function connect()
    {
        $this->conn = new mysqli(
            $_ENV['DB_HOST'],
            $_ENV['DB_USERNAME'],
            $_ENV['DB_PASSWORD'],
            $_ENV['DB_NAME'],
            $_ENV['DB_PORT']
        );

        if ($this->conn->connect_error) {
            die("Database Connection Failed: " . $this->conn->connect_error);
        }

        return $this->conn;
    }
}
