<?php

class Instructor
{

    private $conn;

    public function __construct()
    {
        $db = new Database();
        $this->conn = $db->connect();
    }

    public function totalInstructors($page, $limit)
    {
        try {
            $offset = ($page - 1) * $limit;
            $sql = "SELECT id, name, email, phone, role, isActive FROM users WHERE role = 'Instructor' LIMIT ? OFFSET ?";
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

    public function countInstructor()
    {
        try {
            $sql = "SELECT COUNT(*) as total FROM users WHERE role = 'Instructor'";
            $result = $this->conn->query($sql);
            return ["status" => true , "count" => $result->fetch_assoc()['total']];
        } catch (Exception $e) {
            return ["status" => false, "message" => $e->getMessage()];
        }
    }

    public function getInstructors()
    {
        $sql = "SELECT id, name FROM users WHERE role = 'Instructor'";
        $result = $this->conn->query($sql);

        return $result->fetch_all(MYSQLI_ASSOC);
    }
}
