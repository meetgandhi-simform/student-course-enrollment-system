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

    /**
     * Enroll a student into a course
     *
     * Creates an enrollment and decreases available seats.
     * Uses a transaction to ensure both operations succeed together.
     *
     * @param int $student_id Student ID
     * @param int $course_instructor_id Course-Instructor mapping ID
     * @param int $course_id Course ID (for seat update)
     *
     * @return array Returns:
     * [
     *   "status" => bool,
     *   "id" => int // inserted enrollment ID (on success)
     * ]
     * OR
     * [
     *   "status" => false,
     *   "message" => string
     * ]
     */
    public function enrollStudent($student_id, $course_instructor_id, $course_id)
    {
        try {
            $this->conn->begin_transaction();

            $sql1 = "UPDATE courses 
                SET avail_seats = avail_seats - 1 
                WHERE id = ? AND avail_seats > 0";

            $stmt = $this->conn->prepare($sql1);
            $stmt->bind_param("i", $course_id);
            $stmt->execute();

            if ($stmt->affected_rows === 0) {
                throw new Exception("No seats available!");
            }

            $sql2 = "INSERT INTO enrollments(student_id,course_instructor_id) VALUES (? , ?)";
            $stmt = $this->conn->prepare($sql2);
            $stmt->bind_param("ii", $student_id, $course_instructor_id);
            $stmt->execute();

            $this->conn->commit();
            return ["status" => true, "id" => $this->conn->insert_id];
        } catch (Exception $e) {
            $this->conn->rollback();
            return ["status" => false, "message" => $e->getMessage()];
        }
    }

    /**
     * Get instructors for a specific course
     *
     * @param int $course_id Course ID
     * 
     * @return array Status and instructor list OR error message
     */
    public function getInstructorPerCourse($course_id)
    {
        try {
            $sql = "SELECT 
                        ci.id as id,
                        ci.instructor_id as instructor_id,
                        u.name as name 
                    FROM course_instructor ci 
                    INNER JOIN users u on ci.instructor_id = u.id 
                    WHERE course_id = ?";
            $stmt = $this->conn->prepare($sql);
            $stmt->bind_param("i", $course_id);
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
     * Get all enrollments with pagination
     *
     * @param int $page Current page number
     * @param int $limit Number of records per page
     * 
     * @return array Status and enrollment data OR error message
     */
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
                    ORDER BY e.id
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

    /**
     * Cancel an enrollment
     *
     * @param int $id Enrollment ID
     * 
     * @return array Status and message
     */
    public function deleteEnrollment($id)
    {
        try {
            $sql = "update enrollments set status = 'Cancelled' where id = ?";
            $stmt = $this->conn->prepare($sql);
            $stmt->bind_param("i", $id);
            $stmt->execute();

            return ["status" => true, "message" => "Enrollment Deleted Successfully!!"];
        } catch (Exception $e) {
            return ["status" => false, "message" => $e->getMessage()];
        }
    }

    /**
     * Activate an enrollment
     *
     * @param int $id Enrollment ID
     * 
     * @return array Status and message
     */
    public function activeEnrollment($id)
    {
        try {
            $sql = "UPDATE enrollments SET status = 'Enrolled' WHERE id = ?";
            $stmt = $this->conn->prepare($sql);
            $stmt->bind_param("i", $id);
            $stmt->execute();

            return ["status" => true, "message" => "Reenrolled Successfully!!"];
        } catch (Exception $e) {
            return ["status" => false, "message" => $e->getMessage()];
        }
    }

    public function completeEnrollment($id)
    {
        try {
            $sql = "UPDATE enrollments SET status = 'Completed' WHERE id = ?";
            $stmt = $this->conn->prepare($sql);
            $stmt->bind_param("i", $id);
            $stmt->execute();

            return ["status" => true, "message" => "Course Completed Successfully!!"];
        } catch (Exception $e) {
            return ["status" => false, "message" => $e->getMessage()];
        }
    }

    public function getEnrollmentsById($id, $page, $limit)
    {
        try {
            $offset = ($page - 1) * $limit;
            $sql = "SELECT
	                    c.id as id, 
                        c.course_name as name,
                        c.duration_weeks as weeks,
                        ci.instructor_id as instructor_id,
                        u.name as instructor_name,
                        e.status as status,
                        e.id as enrollment_id
                    FROM courses c 
                    JOIN course_instructor ci ON c.id = ci.course_id
                    JOIN enrollments e ON ci.id = e.course_instructor_id
                    JOIN users u ON u.id = ci.instructor_id
                    WHERE e.student_id = ?
                    LIMIT ? OFFSET ?";
            $stmt = $this->conn->prepare($sql);
            $stmt->bind_param("iii", $id, $limit, $offset);
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

    public function countEnrollmentsByStudentId($id)
    {
        try {
            $sql = "SELECT COUNT(*) as total
                    FROM courses c 
                    JOIN course_instructor ci ON c.id = ci.course_id
                    JOIN enrollments e ON ci.id = e.course_instructor_id
                    JOIN users u ON u.id = ci.instructor_id
                    WHERE e.student_id = ?";
            $stmt = $this->conn->prepare($sql);
            $stmt->bind_param("i", $id);
            $stmt->execute();
            $result = $stmt->get_result();
            $row = $result->fetch_assoc();

            return ["status" => true, "total" => (int)$row['total']];
        } catch (Exception $e) {
            return ["status" => false, "message" => $e->getMessage()];
        }
    }
}
