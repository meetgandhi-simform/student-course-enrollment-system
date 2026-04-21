<?php
class Database
{

    public $conn;

    public function connect()
    {
        $config = require "/var/www/html/course-management/config/config.php";

        $db = $config['db'];


        $this->conn = new mysqli(
            $db['host'],
            $db['username'],
            $db['password'],
            $db['dbname'],
            $db['port']
        );

        if ($this->conn->connect_error) {
            die("Database Connection Failed: " . $this->conn->connect_error);
        }

        return $this->conn;
    }
}
?>