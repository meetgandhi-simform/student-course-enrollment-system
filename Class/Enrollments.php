<?php
require_once 'Database.php';
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

    public function allEnrollments($page, $limit)
    {
        try {
            $offset = ($page - 1) * $limit;
            $sql = "SELECT 
                        e.id as enrollment_id, 
                        c.course_name as course_name, 
                        s.id as student_id, 
                        s.name as student_name, 
                        i.id as instructor_id, 
                        i.name as instructor_name,
                        e.status as enrollment_status 
                    FROM enrollments e
                    INNER JOIN users s ON e.student_id = s.id
                    INNER JOIN course_instructor ci ON ci.id = e.course_instructor_id
                    INNER JOIN users i ON ci.instructor_id = i.id
                    INNER JOIN courses c ON ci.course_id = c.id
                    LIMIT ? OFFSET ?";
            $stmt = $this->conn->prepare($sql);
            $stmt->bind_param("ii", $limit, $offset);
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

    public function deleteEnrollment($id){
        try{
            $sql = "update enrollments set status = 'Cancelled' where id = ?";
            $stmt = $this->conn->prepare($sql);
            $stmt->bind_param("i",$id);
            $stmt->execute();

            return["status" => true , "message" => "Enrollment Deleted Successfully!!"];
        }catch(Exception $e){
            return ["status" => false , "message" => $e->getMessage()];
        }
    }

    public function activeEnrollment($id){
        try {
            $sql = "update enrollments set status = 'Enrolled' where id = ?";
            $stmt = $this->conn->prepare($sql);
            $stmt->bind_param("i", $id);
            $stmt->execute();

            return ["status" => true, "message" => "Enrolled Successfully!!"];
        } catch (Exception $e) {
            return ["status" => false, "message" => $e->getMessage()];
        }
    }
}
