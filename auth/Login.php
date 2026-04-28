<?php

require_once __DIR__ . "./../Class/Database.php";

/**
 * Class Login
 * 
 * Handles authentication logic such as:
 * - Verifying user credentials
 * - Returning authenticated user data
 */
class Login
{

    /**
     * @var mysqli Database connection instance
     */
    private $conn;

    /**
     * Login constructor.
     * Initializes database connection.
     */
    public function __construct()
    {
        $db = new Database();
        $this->conn = $db->connect();
    }

    /**
     * Authenticate user using email and password
     *
     * @param string $email User email
     * @param string $password Plain text password
     * 
     * @return array|false Returns user data on success or false on failure
     */
    public function login($email, $password)
    {
        $sql = "SELECT id, name, email, password, isActive, role FROM users WHERE email = ? limit 1";
        $stmt = $this->conn->prepare($sql);
        $stmt->bind_param("s", $email);
        $stmt->execute();

        $result = $stmt->get_result()->fetch_assoc();

        if (!$result) {
            return false;
        }
        if (password_verify($password, $result['password'])) {
            return [
                'id' => $result['id'],
                'name' => $result['name'],
                'email' => $result['email'],
                'isActive' => $result['isActive'],
                'role' => $result['role']
            ];
        }

        return false;
    }
}
