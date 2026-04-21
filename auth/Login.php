<?php

require_once __DIR__ . "./../Class/Database.php";

class Login{
    private $conn;

    public function __construct()
    {
        $db = new Database();
        $this->conn = $db->connect();
    }
    public function login($email, $password)
    {
        $sql = "SELECT id, name, email, password, role FROM users WHERE email = ? limit 1";
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
                'role' => $result['role']
            ];
        }

        return false;
    }
}
?>