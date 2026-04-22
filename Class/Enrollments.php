<?php

/**
 * Class Enrollments
 * 
 * Handles enrollment-related operations such as:
 * - Counting total enrollments
 */
class Enrollments
{
    /**
     * @var mysqli Database connection instance
     */
    private $conn;

    /**
     * Enrollments constructor.
     * Initializes database connection.
     */
    public function __construct()
    {
        $db = new Database();
        $this->conn = $db->connect();
    }

    /**
     * Count total number of enrollments
     *
     * @return array Returns status and total count OR error message
     */
    public function countEnrollments()
    {
        try {
            $sql = "SELECT COUNT(*) as total FROM enrollments";
            $result = $this->conn->query($sql);
            return ["status" => true, "count" => $result->fetch_assoc()['total']];
        } catch (Exception $e) {
            return ["status" => false, "message" => $e->getMessage()];
        }
    }
}
