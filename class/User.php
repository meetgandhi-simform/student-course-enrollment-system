<?php


require_once "EmailQueue.php";

/**
 * Class User
 * 
 * Handles all user-related database operations such as:
 * - Creating users
 * - Fetching users (admins/students)
 * - Counting users
 * - Activating/Deactivating users
 * - Updating user details
 */

class User
{
    /**
     * @var mysqli Database connection instance
     */
    private $conn;
    /**
     * Constructor - Initailize database connection
     */

    public function __construct()
    {
        $db = new Database();
        $this->conn = $db->connect();
    }

    /**
     * Create a new user
     *
     * @param string $name User name
     * @param string $email User email
     * @param string $password Plain password (will be hashed)
     * @param string $phone User phone number
     * @param string $role User role (Admin/Student/etc.)
     * 
     * @return int|array Returns inserted ID on success or error array on failure
     */

    public function createUser($name, $email, $password, $phone, $role)
    {
        try {
            $hashedPassword = password_hash($password, PASSWORD_DEFAULT);
            $sql = "INSERT INTO users (name, email, password, phone, role)
            VALUES (?, ?, ?, ?, ?)";
            $stmt = $this->conn->prepare($sql);
            $stmt->bind_param("sssss", $name, $email, $hashedPassword, $phone, $role);
            $stmt->execute();

            return ["status" => true, "id" => $this->conn->insert_id];
        } catch (Exception $e) {
            return ["status" => false, "message" => $e->getMessage()];
        }
    }

    /**
     * Bulk insert users and queue emails
     *
     * Inserts only valid rows from the provided data array.
     * Passwords are hashed before storing. Skips invalid or failed rows.
     * Queues welcome emails after successful insert.
     *
     * @param array $data Array of user records (validated)
     *
     * @return array Returns ['status' => true, 'inserted' => int] on success
     *               or ['status' => false, 'message' => string] on failure
     */
    public function bulkInsertUsers($data)
    {
        $this->conn->begin_transaction();

        try {
            $sql = "INSERT INTO users (name, email, password, phone, role)
                VALUES (?, ?, ?, ?, ?)";
            $stmt = $this->conn->prepare($sql);
            $emailQueueObj = new EmailQueue();
            $count = 0;

            foreach ($data as $row) {

                if (!$row['is_valid']) {
                    continue;
                }

                try {
                    $hashedPassword = password_hash($row['password'], PASSWORD_DEFAULT);
                    $stmt->bind_param(
                        "sssss",
                        $row['name'],
                        $row['email'],
                        $hashedPassword,
                        $row['phone'],
                        $row['role']
                    );
                    $stmt->execute();
                    $count++;
                    $subject = "Your Account Has Been Created - Student Enrollment System";
                    $message = "
            <div style='font-family: Arial, sans-serif; max-width: 600px; margin: auto; padding: 20px; border: 1px solid #ddd; border-radius: 10px;'>

                <h2 style='color: #2c3e50; text-align: center;'>🎓 Student Enrollment System</h2>
                <p>Hello,</p>
                <p>An administrator has created an account for you on the <b>Student Enrollment System</b>.</p>

                <p style='background-color: #f4f6f7; padding: 10px; border-left: 4px solid #3498db;'>
                    <b>Role:</b> {$row['role']} <br>
                    <b>Email:</b> {$row['email']} <br>
                    <b>Password:</b>{$row['password']} <br>
                </p>

                <p>You can now log in using your credentials.</p>

                    <div style='text-align: center; margin-top: 20px;'>
                        <a href='http://172.16.7.30:8103/course-management/ui/login.php' 
                            style='background-color: #3498db; color: white; padding: 10px 20px; text-decoration: none; border-radius: 5px;'>
                            Login Now
                        </a>
                    </div>

                <br>
                <p>If you did not expect this email, please contact your administrator.</p>
                <p>Best Regards,<br>
                <b>Student Enrollment Team</b></p>
        </div>";

                    $emailQueueObj->addEmail(
                        $row['email'],
                        $subject,
                        $message
                    );
                } catch (Exception $rowException) {
                    error_log("Row failed: " . $rowException->getMessage());
                    continue;
                }
            }

            $this->conn->commit();

            return [
                "status" => true,
                "inserted" => $count
            ];
        } catch (Exception $e) {

            $this->conn->rollback();

            return [
                "status" => false,
                "message" => $e->getMessage()
            ];
        }
    }

    /**
     * Get paginated list of admins
     *
     * @param int $page Current page number
     * @param int $limit Number of records per page
     * 
     * @return array Response with status and data
     */

    public function totalAdmins($page, $limit)
    {
        try {
            $offset = ($page - 1) * $limit;
            $sql = "SELECT * FROM users WHERE role = 'Admin' ORDER BY id LIMIT ? OFFSET ?";
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
     * Get paginated list of students
     *
     * @param int $page Current page
     * @param int $limit Records per page
     * 
     * @return array Response with status and data
     */

    public function totalStudents($page, $limit)
    {
        try {
            $offset = ($page - 1) * $limit;
            $sql = "SELECT * FROM users WHERE role = 'Student' LIMIT ? OFFSET ?";
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
     * Count total admins
     *
     * @return array Status and count
     */

    public function countAdmins()
    {
        try {
            $sql = "SELECT COUNT(*) as total FROM users WHERE role = 'admin'";
            $result = $this->conn->query($sql);
            return ["status" => true, "count" => $result->fetch_assoc()['total']];
        } catch (Exception $e) {
            return ["status" => false, "message" => $e->getMessage()];
        }
    }

    /**
     * Count total students
     *
     * @return array Status and count
     */

    public function countStudents()
    {
        try {
            $sql = "SELECT COUNT(*) as total FROM users WHERE role = 'Student'";
            $result = $this->conn->query($sql);
            return ["status" => true, "count" => $result->fetch_assoc()['total']];
        } catch (Exception $e) {
            return ["status" => false, "message" => $e->getMessage()];
        }
    }

    /**
     * Count active users
     *
     * @return array Status and count
     */

    public function countActiveUsers()
    {
        try {
            $sql = "SELECT COUNT(*) as total FROM users WHERE isActive = 'Active'";
            $result = $this->conn->query($sql);
            return ["status" => true, "count" => $result->fetch_assoc()['total']];
        } catch (Exception $e) {
            return ["status" => false, "message" => $e->getMessage()];
        }
    }

    /**
     * Soft delete a user (mark as inactive)
     *
     * @param int $id User ID
     * 
     * @return array Status and message
     */

    public function deleteUsers($id)
    {
        try {
            $sql = "UPDATE users set isActive = 'Inactive' where id = ?";
            $stmt = $this->conn->prepare($sql);
            $stmt->bind_param("i", $id);
            $stmt->execute();

            return ["status" => true, "Message" => "User Deleted Successfully!!"];
        } catch (Exception $e) {
            return ["status" => false, "Message" => $e->getMessage()];
        }
    }

    /**
     * Activate a user
     *
     * @param int $id User ID
     * 
     * @return array Status and message
     */


    public function activateUser($id)
    {
        try {
            $sql = "UPDATE users SET isActive = 'Active' WHERE id = ?";
            $stmt = $this->conn->prepare($sql);
            $stmt->bind_param("i", $id);
            $stmt->execute();

            return ["status" => true, "Message" => "User Activated Sucessfully!!"];
        } catch (Exception $e) {
            return ["status" => false, "Message" => $e->getMessage()];
        }
    }

    /**
     * Get user by ID
     *
     * @param int $id User ID
     * 
     * @return array|null User data or null if not found
     */

    public function getUserById($id)
    {
        $sql = "SELECT id, name, email, phone FROM users WHERE id = ?";
        $stmt = $this->conn->prepare($sql);
        $stmt->bind_param("i", $id);
        $stmt->execute();

        $result = $stmt->get_result();
        return $result->fetch_assoc();
    }

    /**
     * Get all students (id and name)
     *
     * @return array Status and student list OR error message
     */
    public function getStudents()
    {
        $sql = "SELECT id, name FROM users WHERE role = 'Student'";
        $stmt = $this->conn->prepare($sql);
        $stmt->execute();

        $result = $stmt->get_result();
        $data = [];

        while ($row = $result->fetch_assoc()) {
            $data[] = $row;
        }

        return ["status" => true, "data" => $data];
    }

    /**
     * Update user (partial update / PATCH-like behavior)
     *
     * @param int $id User ID
     * @param array $data Associative array of fields to update
     *                    Example: ['name' => 'John', 'email' => 'abc@mail.com']
     * 
     * @return array Status and result info
     */

    public function updateUser($id, $data)
    {
        try {
            $fields = [];
            $values = [];
            $types = "";

            if (empty($data) || empty($id)) {
                echo "<script> 
                alert('Nothing To Update');
                window.history.back();
                </script>";
            }

            if (!empty($data['name'])) {
                $fields[] = "name = ?";
                $values[] = $data['name'];
                $types .= 's';
            }

            if (!empty($data['email'])) {
                $fields[] = "email = ?";
                $values[] = $data['email'];
                $types .= 's';
            }

            if (!empty($data['phone'])) {
                $fields[] = "phone = ?";
                $values[] = $data['phone'];
                $types .= 's';
            }

            $sql = "UPDATE users SET " . implode(', ', $fields) . " WHERE id = ?";
            $values[] = $id;
            $types .= "i";

            $stmt = $this->conn->prepare($sql);
            $stmt->bind_param($types, ...$values);
            $stmt->execute();

            return ["status" => true, "id" =>  $stmt->affected_rows];
        } catch (Exception $e) {
            return ["status" => false, "message" => $e->getMessage()];
        }
    }

    /**
     * Get paginated list of students enrolled under a specific instructor
     *
     * @param int $instructor_id Instructor ID
     * @param int $page Current page number
     * @param int $limit Number of records per page
     *
     * @return array{
     *     status: bool,
     *     data?: array<int, array{
     *         id:int,
     *         name:string,
     *         email:string,
     *         phone:int,
     *         course_name:string,
     *         isActive:int
     *     }>,
     *     message?: string
     * }
     */
    public function getStudentsByInstructor($instructor_id, $page, $limit)
    {
        try {
            $offset = ($page - 1) * $limit;
            $sql = "SELECT 
                        us.id,
                        us.name,
                        us.email,
                        us.phone,
                        c.course_name,
                        us.isActive 
                    FROM enrollments e
                    JOIN course_instructor ci ON ci.id = e.course_instructor_id
                    JOIN courses c ON c.id = ci.course_id
                    JOIN users us ON us.id = e.student_id
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
     * Count total students enrolled under a specific instructor
     *
     * @param int $instructor_id Instructor ID
     *
     * @return array{status: bool, total?: int, message?: string}
     */
    public function countStudentsByInstructor($instructor_id)
    {
        try {
            $sql = "SELECT COUNT(*) as total
                FROM enrollments e
                JOIN course_instructor ci ON ci.id = e.course_instructor_id
                WHERE ci.instructor_id = ?";

            $stmt = $this->conn->prepare($sql);
            $stmt->bind_param("i", $instructor_id);
            $stmt->execute();

            $result = $stmt->get_result();
            $row = $result->fetch_assoc();

            return ["status" => true, "total" => $row['total']];
        } catch (Exception $e) {
            return ["status" => false, "message" => $e->getMessage()];
        }
    }

    /**
     * Get paginated list of students (server-side processing for DataTables)
     *
     * Supports search, filtering, and sorting for DataTables server-side rendering.
     * Returns paginated student records with search capability.
     *
     * @param int $start Starting index for pagination
     * @param int $length Number of records to return
     * @param string $search Search term to filter students by name, email, or phone
     * @param string $orderColumn Column name to order by
     * @param string $orderDirection Sort direction (ASC or DESC)
     * @param int $draw Draw counter from DataTables request
     *
     * @return array{draw: int, recordsTotal: int, recordsFiltered: int, data: array, error?: string}
     */
    public function getStudentsServerSide($start, $length, $search, $orderColumn, $orderDirection, $draw)
    {
        try {
            $baseQuery = "FROM users WHERE role = 'Student'";

            // SEARCH
            if (!empty($search)) {
                $baseQuery .= "
                AND (
                    name LIKE ? OR
                    email LIKE ? OR
                    phone LIKE ?
                )
            ";
            }

            // TOTAL RECORDS
            $totalQuery = "SELECT COUNT(*) as total FROM users WHERE role='Student'";
            $totalResult = $this->conn->query($totalQuery);
            $totalRecords = $totalResult->fetch_assoc()['total'];

            // FILTERED RECORDS
            $filteredQuery = "SELECT COUNT(*) as total " . $baseQuery;
            $stmt = $this->conn->prepare($filteredQuery);
            if (!empty($search)) {

                $searchTerm = "%{$search}%";

                $stmt->bind_param(
                    "sss",
                    $searchTerm,
                    $searchTerm,
                    $searchTerm
                );
            }

            $stmt->execute();
            $filteredResult = $stmt->get_result();
            $filteredRecords = $filteredResult->fetch_assoc()['total'];

            // MAIN DATA QUERY
            $dataQuery = "
                SELECT *
                {$baseQuery}
                ORDER BY {$orderColumn} {$orderDirection}
                LIMIT ? OFFSET ?
            ";
            $stmt = $this->conn->prepare($dataQuery);
            if (!empty($search)) {
                $searchTerm = "%{$search}%";
                $stmt->bind_param(
                    "sssii",
                    $searchTerm,
                    $searchTerm,
                    $searchTerm,
                    $length,
                    $start
                );
            } else {
                $stmt->bind_param(
                    "ii",
                    $length,
                    $start
                );
            }
            $stmt->execute();
            $result = $stmt->get_result();
            $data = [];
            while ($row = $result->fetch_assoc()) {

                $data[] = $row;
            }

            return [
                "draw" => intval($draw),
                "recordsTotal" => intval($totalRecords),
                "recordsFiltered" => intval($filteredRecords),
                "data" => $data
            ];
        } catch (Exception $e) {

            return [
                "draw" => intval($draw),
                "recordsTotal" => 0,
                "recordsFiltered" => 0,
                "data" => [],
                "error" => $e->getMessage()
            ];
        }
    }

    /**
     * Get paginated list of admins (server-side processing for DataTables)
     *
     * Supports search, filtering, and sorting for DataTables server-side rendering.
     * Returns paginated admin records with search capability.
     *
     * @param int $start Starting index for pagination
     * @param int $length Number of records to return
     * @param string $search Search term to filter admins by name, email, or phone
     * @param string $orderColumn Column name to order by
     * @param string $orderDirection Sort direction (ASC or DESC)
     * @param int $draw Draw counter from DataTables request
     *
     * @return array{draw: int, recordsTotal: int, recordsFiltered: int, data: array, error?: string}
     */
    public function getAdminsServerSide($start, $length, $search, $orderColumn, $orderDirection, $draw)
    {
        try {
            $baseQuery = "FROM users WHERE role = 'Admin'";

            // SEARCH
            if (!empty($search)) {
                $baseQuery .= "
                AND (
                    name LIKE ? OR
                    email LIKE ? OR
                    phone LIKE ?
                )
            ";
            }

            // TOTAL RECORDS
            $totalQuery = "
            SELECT COUNT(*) as total FROM users WHERE role = 'Admin'";
            $totalResult = $this->conn->query($totalQuery);
            $totalRecords = $totalResult->fetch_assoc()['total'];

            // FILTERED RECORDS
            $filteredQuery = " SELECT COUNT(*) as total {$baseQuery}";
            $stmt = $this->conn->prepare($filteredQuery);
            if (!empty($search)) {
                $searchTerm = "%{$search}%";
                $stmt->bind_param(
                    "sss",
                    $searchTerm,
                    $searchTerm,
                    $searchTerm
                );
            }

            $stmt->execute();
            $filteredResult = $stmt->get_result();
            $filteredRecords = $filteredResult->fetch_assoc()['total'];

            // MAIN DATA QUERY
            $dataQuery = "
            SELECT *
            {$baseQuery}
            ORDER BY {$orderColumn} {$orderDirection}
            LIMIT ? OFFSET ?
        ";

            $stmt = $this->conn->prepare($dataQuery);
            if (!empty($search)) {
                $searchTerm = "%{$search}%";
                $stmt->bind_param(
                    "sssii",
                    $searchTerm,
                    $searchTerm,
                    $searchTerm,
                    $length,
                    $start
                );
            } else {

                $stmt->bind_param(
                    "ii",
                    $length,
                    $start
                );
            }

            $stmt->execute();
            $result = $stmt->get_result();
            $data = [];
            while ($row = $result->fetch_assoc()) {
                $data[] = $row;
            }

            return [
                "draw" => intval($draw),
                "recordsTotal" => intval($totalRecords),
                "recordsFiltered" => intval($filteredRecords),
                "data" => $data
            ];
        } catch (Exception $e) {
            return [
                "draw" => intval($draw),
                "recordsTotal" => 0,
                "recordsFiltered" => 0,
                "data" => [],
                "error" => $e->getMessage()
            ];
        }
    }
    /**
     * Get students enrolled under a specific instructor (server-side processing for DataTables)
     *
     * Supports search, filtering, and sorting for DataTables server-side rendering.
     * Returns paginated student records enrolled under the specified instructor with search capability.
     *
     * @param int $draw Draw counter from DataTables request
     * @param int $instructor_id Instructor ID to filter students by
     * @param int $start Starting index for pagination
     * @param int $length Number of records to return
     * @param string $search Search term to filter by student name, email, course name, or status
     * @param string $orderColumn Column name to order by
     * @param string $orderDirection Sort direction (ASC or DESC)
     *
     * @return array{draw: int, recordsTotal: int, recordsFiltered: int, data: array, error?: string}
     */
    public function getStudentsByInstructorServerSide($draw, $instructor_id, $start, $length, $search, $orderColumn, $orderDirection)
    {
        try {
            $baseQuery = "
            FROM enrollments e
            JOIN course_instructor ci
            ON ci.id = e.course_instructor_id
            JOIN courses c
            ON c.id = ci.course_id
            JOIN users us
            ON us.id = e.student_id
            WHERE ci.instructor_id = ?
        ";

            // SEARCH
            if (!empty($search)) {
                $baseQuery .= "
                AND (
                    us.name LIKE ? OR
                    us.email LIKE ? OR
                    c.course_name LIKE ? OR
                    us.isActive LIKE ?
                )
            ";
            }

            // TOTAL RECORDS

            $totalQuery = "
            SELECT COUNT(*) as total
            FROM enrollments e
            JOIN course_instructor ci
            ON ci.id = e.course_instructor_id
            WHERE ci.instructor_id = ?
        ";

            $stmt = $this->conn->prepare($totalQuery);
            $stmt->bind_param("i", $instructor_id);
            $stmt->execute();
            $totalResult = $stmt->get_result();
            $totalRecords = $totalResult->fetch_assoc()['total'];

            // FILTERED RECORDS

            $filteredQuery = "
            SELECT COUNT(*) as total
            {$baseQuery}
        ";

            $stmt = $this->conn->prepare($filteredQuery);
            if (!empty($search)) {
                $searchTerm = "%{$search}%";
                $stmt->bind_param(
                    "issss",
                    $instructor_id,
                    $searchTerm,
                    $searchTerm,
                    $searchTerm,
                    $searchTerm
                );
            } else {
                $stmt->bind_param(
                    "i",
                    $instructor_id
                );
            }

            $stmt->execute();
            $filteredResult = $stmt->get_result();
            $filteredRecords = $filteredResult->fetch_assoc()['total'];

            // MAIN DATA QUERY
            $dataQuery = "
            SELECT
                us.id,
                us.name,
                us.email,
                us.phone,
                c.course_name,
                us.isActive
            {$baseQuery}
            ORDER BY {$orderColumn} {$orderDirection}
            LIMIT ? OFFSET ?
        ";

            $stmt = $this->conn->prepare($dataQuery);
            if (!empty($search)) {
                $searchTerm = "%{$search}%";
                $stmt->bind_param(
                    "issssii",
                    $instructor_id,
                    $searchTerm,
                    $searchTerm,
                    $searchTerm,
                    $searchTerm,
                    $length,
                    $start
                );
            } else {
                $stmt->bind_param(
                    "iii",
                    $instructor_id,
                    $length,
                    $start
                );
            }

            $stmt->execute();
            $result = $stmt->get_result();
            $data = [];
            while ($row = $result->fetch_assoc()) {
                $data[] = $row;
            }
            return [
                "draw" => intval($draw),
                "recordsTotal" => intval($totalRecords),
                "recordsFiltered" => intval($filteredRecords),
                "data" => $data
            ];
        } catch (Exception $e) {
            return [
                "draw" => intval($draw),
                "recordsTotal" => 0,
                "recordsFiltered" => 0,
                "data" => [],
                "error" => $e->getMessage()
            ];
        }
    }
}
