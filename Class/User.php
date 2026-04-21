<?php

require_once 'Database.php';

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

            return $this->conn->insert_id;
        } catch (Exception $e) {
            return ["status" => false, "message" => $e->getMessage()];
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
            $sql = "SELECT * FROM users WHERE role = 'Admin' LIMIT ? OFFSET ?";
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
}
?>
