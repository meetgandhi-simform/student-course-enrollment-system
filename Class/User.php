<?php

require_once 'Database.php';

class User
{

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
     * @param string $name
     * @param string $email
     * @param string $password
     * @param int $phone
     * @param string $role
     * @return int|array Returns Inserted id on success or error array in failure
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

    public function getUserById($id)
    {
        $sql = "SELECT id, name, email, phone FROM users WHERE id = ?";
        $stmt = $this->conn->prepare($sql);
        $stmt->bind_param("i", $id);
        $stmt->execute();

        $result = $stmt->get_result();
        return $result->fetch_assoc();
    }

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
            
            print_r($data);

            return ["status" => true,"id" =>  $this->conn->insert_id];
        } catch (Exception $e) {
            return ["status" => false,"message" => $e->getMessage()];
        }
    }
}
?>
