<?php

require_once 'Database.php';

/**
 * Class Course
 * 
 * Handles course-related operations such as:
 * - Creating courses
 * - Assigning instructors
 * - Fetching course data
 * - Pagination and counting
 * - Removing instructor assignments
 */

class Course
{

    /**
     * @var mysqli Database connection instance
     */

    private $conn;

    /**
     * Course constructor.
     * Initializes database connection.
     */


    public function __construct()
    {
        $db = new Database();
        $this->conn = $db->connect();
    }

    /**
     * Create a new course
     *
     * @param string $name Course name
     * @param int $durationInWeeks Duration in weeks
     * @param int $seats Maximum seats available
     * 
     * @return int|array Returns inserted course ID on success or error array on failure
     */

    public function createCourse($name, $durationInWeeks, $seats)
    {
        try {
            $sql = "INSERT INTO courses(course_name,duration_weeks,max_seats,avail_seats) VALUES (?,?,?,?)";
            $stmt = $this->conn->prepare($sql);
            $stmt->bind_param("siii", $name, $durationInWeeks, $seats, $seats);
            $stmt->execute();

            return $this->conn->insert_id;
        } catch (Exception $e) {
            return ["status" => false, "message" => $e->getMessage()];
        }
    }

    /**
     * Assign an instructor to a course
     *
     * @param int $instructor_id Instructor ID
     * @param int $course_id Course ID
     * 
     * @return int|array Returns inserted ID or error array
     */

    public function assignInstructor($instructor_id, $course_id)
    {
        try {
            $sql = "INSERT INTO course_instructor (instructor_id,course_id) values (?,?)";
            $stmt = $this->conn->prepare($sql);
            $stmt->bind_param("ii", $instructor_id, $course_id);
            $stmt->execute();

            return $this->conn->insert_id;
        } catch (Exception $e) {
            return ["status" => false, "message" => $e->getMessage()];
        }
    }

    /**
     * Get all courses (basic info)
     *
     * @return array List of courses with id and name
     */
    public function getCourses()
    {
        $sql = "SELECT id, course_name FROM courses";
        $result = $this->conn->query($sql);

        return $result->fetch_all(MYSQLI_ASSOC);
    }

    /**
     * Get all courses for dropdown/options
     *
     * @return array Status and data OR error message
     */
    public function getOptionCourses()
    {
        try {
            $sql = "SELECT id, course_name FROM courses";
            $stmt = $this->conn->prepare($sql);
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
     * Get courses with instructor details (paginated)
     *
     * @param int $page Current page number
     * @param int $limit Number of records per page
     * 
     * @return array Status and data OR error message
     */

    public function courseWithInstructor($page, $limit)
    {
        try {
            $offset = ($page - 1) * $limit;
            $sql = "SELECT 
                        c.id,
                        c.course_name,
                        c.max_seats,
                        c.avail_seats,
                        ci.instructor_id,
                        u.name,
                        u.isActive 
                FROM courses c
                INNER JOIN course_instructor ci ON c.id = ci.course_id
                INNER JOIN users u on u.id = ci.instructor_id
                ORDER BY c.id
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
     * Get paginated list of courses assigned to a specific instructor
     *
     * @param int $instructor_id Instructor ID
     * @param int $page Current page number
     * @param int $limit Number of records per page
     *
     * @return array{status: bool, data?: array<int, array<string, mixed>>, message?: string}
     */
    public function getCourseByInstructor($instructor_id, $page, $limit)
    {
        try {
            $offset = ($page - 1) * $limit;
            $sql = "SELECT 
	                    c.id,
                        c.course_name,
                        c.max_seats,
                        c.avail_seats 
                    FROM courses c 
                    JOIN course_instructor ci ON c.id = ci.course_id
                    WHERE ci.instructor_id = ?
                    LIMIT ? OFFSET ?";
            $stmt = $this->conn->prepare($sql);
            $stmt->bind_param("iii", $instructor_id, $limit, $offset);
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
     * Count total courses assigned to a specific instructor
     *
     * @param int $instructor_id Instructor ID
     *
     * @return array{status: bool, count?: int, message?: string}
     */
    public function countCourseByInstructor($instructor_id)
    {
        try {
            $sql = "SELECT COUNT(*) AS total 
                FROM courses c
                JOIN course_instructor ci ON c.id = ci.course_id
                WHERE ci.instructor_id = ?";

            $stmt = $this->conn->prepare($sql);
            $stmt->bind_param("i", $instructor_id);
            $stmt->execute();

            $result = $stmt->get_result();
            $row = $result->fetch_assoc();

            return [
                "status" => true,
                "count" => (int) $row['total']
            ];
        } catch (Exception $e) {
            return [
                "status" => false,
                "message" => $e->getMessage()
            ];
        }
    }

    /**
     * Get all courses (id & name) for dropdown/select options by instructor
     *
     * @param int $instructor_id Instructor ID
     *
     * @return array{status: bool, data?: array<int, array{id:int, course_name:string}>, message?: string}
     */
    public function getOptionCourseByInstructor($instructor_id)
    {
        try {
            $sql = "SELECT 
                        c.id ,
                        c.course_name 
                    FROM courses c 
                    JOIN course_instructor ci ON ci.course_id = c.id
                    WHERE ci.instructor_id = ?";
            $stmt = $this->conn->prepare($sql);
            $stmt->bind_param("i", $instructor_id);
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
     * Count total courses with assigned instructors
     *
     * @return array Status and total count OR error message
     */
    public function countCourseWithInstructor()
    {
        try {
            $sql = "SELECT COUNT(*) as total FROM courses c
                INNER JOIN course_instructor ci ON c.id = ci.course_id
                INNER JOIN users u ON u.id = ci.instructor_id";
            $result = $this->conn->query($sql);
            return  ["status" => true, "count" => $result->fetch_assoc()['total']];
        } catch (Exception $e) {
            return ["status" => false, "message" => $e->getMessage()];
        }
    }

    /**
     * Count total number of courses
     *
     * @return array Status and total count OR error message
     */
    public function countCourses()
    {
        try {
            $sql = "SELECT COUNT(*) as total FROM courses";
            $result = $this->conn->query($sql);
            return ["status" => true, "count" => $result->fetch_assoc()['total']];
        } catch (Exception $e) {
            return ["status" => false, "message" => $e->getMessage()];
        }
    }

    /**
     * Remove an instructor from a course
     *
     * @param int $course_id Course ID
     * @param int $instructor_id Instructor ID
     * 
     * @return array Status and message
     */

    public function deleteCourseInstructor($course_id, $instructor_id)
    {
        try {
            $sql = "DELETE FROM course_instructor 
                WHERE course_id = ? AND instructor_id = ?";

            $stmt = $this->conn->prepare($sql);
            $stmt->bind_param("ii", $course_id, $instructor_id);
            $stmt->execute();

            return ["status" => true, "message" => "Instructor removed from course"];
        } catch (Exception $e) {
            return ["status" => false, "message" => $e->getMessage()];
        }
    }

    /**
     * Update available seats for a course
     *
     * Decreases available seats by 1 only if seats are available.
     *
     * @param int $course_id Course ID
     * 
     * @return array Status and message
     */
    public function updateSeats($course_id)
    {
        try {
            $sql = "UPDATE courses 
                SET avail_seats = avail_seats - 1 
                WHERE id = ? AND avail_seats > 0";

            $stmt = $this->conn->prepare($sql);
            $stmt->bind_param("i", $course_id);
            $stmt->execute();

            return ["status" => true, "message" => "Seats updated successfully!"];
        } catch (Exception $e) {
            return ["status" => false, "message" => $e->getMessage()];
        }
    }

    /**
     * Get course details by ID
     *
     * Fetches course ID and available seats.
     *
     * @param int $course_id Course ID
     * 
     * @return array Status and data OR false status if not found
     */
    public function getCourseById($course_id)
    {
        $sql = "SELECT id, avail_seats FROM courses WHERE id = ?";
        $stmt = $this->conn->prepare($sql);
        $stmt->bind_param("i", $course_id);
        $stmt->execute();

        $result = $stmt->get_result();
        $data = $result->fetch_assoc();

        if ($data) {
            return ["status" => true, "data" => $data];
        }

        return ["status" => false];
    }
}
