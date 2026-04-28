<?php

/**
 * Class Instructor
 * 
 * Handles instructor-related operations such as:
 * - Fetching paginated instructor list
 * - Counting total instructors
 * - Fetching instructor dropdown data
 */

class Instructor
{

    /**
     * @var mysqli Database connection instance
     */
    private $conn;

    /**
     * Instructor constructor.
     * Initializes database connection.
     */
    public function __construct()
    {
        $db = new Database();
        $this->conn = $db->connect();
    }

    /**
     * Get paginated list of instructors
     *
     * @param int $page Current page number
     * @param int $limit Number of records per page
     * 
     * @return array Returns status and instructor data OR error message
     */
    public function totalInstructors($page, $limit)
    {
        try {
            $offset = ($page - 1) * $limit;
            $sql = "SELECT id, name, email, phone, role, isActive FROM users WHERE role = 'Instructor' ORDER BY id LIMIT ? OFFSET ?";
            $stmt = $this->conn->prepare($sql);
            $stmt->bind_param('ii', $limit, $offset);
            $stmt->execute();

            $result = $stmt->get_result();
            $data = [];

            while ($row = $result->fetch_assoc()) {
                $data[] = $row;
            }

            return ["status" => true, "data" => $data];
        } catch (Exception $e) {
            return ["status" => false, "message" => $e->getMessage()];
        }
    }

    /**
     * Count total number of instructors
     *
     * @return array Returns status and total count OR error message
     */
    public function countInstructor()
    {
        try {
            $sql = "SELECT COUNT(*) as total FROM users WHERE role = 'Instructor'";
            $result = $this->conn->query($sql);
            return ["status" => true, "count" => $result->fetch_assoc()['total']];
        } catch (Exception $e) {
            return ["status" => false, "message" => $e->getMessage()];
        }
    }

    /**
     * Get all instructors (for dropdown/select use)
     *
     * @return array List of instructors with id and name
     */
    public function getInstructors()
    {
        $sql = "SELECT id, name FROM users WHERE role = 'Instructor'";
        $result = $this->conn->query($sql);

        return $result->fetch_all(MYSQLI_ASSOC);
    }
}
