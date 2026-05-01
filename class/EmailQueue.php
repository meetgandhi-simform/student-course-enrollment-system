<?php

require 'Database.php';

class EmailQueue
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

    public function addEmail($email, $subject, $body)
    {
        try {
            $sql = "INSERT INTO email_queue (email, subject, body) values (?,?,?)";
            $stmt = $this->conn->prepare($sql);
            $stmt->bind_param('sss', $email, $subject, $body);
            $stmt->execute();

            return ["status" => true, "id" => $this->conn->insert_id];
        } catch (Exception $e) {
            return ["status" => false, "message" => $e->getMessage()];
        }
    }

    public function getPendingEmail()
    {
        try {
            $sql = "SELECT * FROM email_queue WHERE status = 'pending' LIMIT 1";
            $stmt = $this->conn->prepare($sql);
            $stmt->execute();
            $result = $stmt->get_result()->fetch_assoc();

            return ["status" => true, "data" => $result];
        } catch (Exception $e) {
            return ["status" => false, "message" => $e->getMessage()];
        }
    }

    public function updateStatus($status, $id)
    {
        try {
            $sql = "UPDATE email_queue set status = ? where id = ?";
            $stmt = $this->conn->prepare($sql);
            $stmt->bind_param("si", $status, $id);
            $stmt->execute();
            return ["status" => true, "message" => "Updated Successfuly"];
        } catch (Exception $e) {
            return ["status" => false, "message" => $e->getMessage()];
        }
    }
}
