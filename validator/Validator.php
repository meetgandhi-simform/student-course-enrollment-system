<?php
class Validator
{
    public static function email($email)
    {
        $email = trim($email);

        try {
            if (empty($email)) {
                throw new Exception("Email is required");
            }

            if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
                throw new Exception("Invalid Email Format");
            }

            if (strlen($email) > 255) {
                throw new Exception("Email too long");
            }

            return [
                "status" => true,
                "data" => $email
            ];
        } catch (Exception $e) {
            return [
                "status" => false,
                "message" => $e->getMessage()
            ];
        }
    }

    public static function name($name)
    {
        $name = trim($name);

        try {
            if (empty($name)) {
                throw new Exception("Name is required");
            }

            if (strlen($name) < 2 || strlen($name) > 50) {
                throw new Exception("Name must be between 2 and 50 characters");
            }

            if (!preg_match("/^[a-zA-Z ]+$/", $name)) {
                throw new Exception("Name can only contain letters and spaces");
            }

            return [
                "status" => true,
                "data" => $name
            ];
        } catch (Exception $e) {
            return [
                "status" => false,
                "message" => $e->getMessage()
            ];
        }
    }

    public static function password($password)
    {
        $password = trim($password);

        try {
            if (empty($password)) {
                throw new Exception("Password is required");
            }

            if (strlen($password) < 8) {
                throw new Exception("Password must be at least 8 characters long");
            }

            if (!preg_match("/[A-Z]/", $password)) {
                throw new Exception("Password must contain at least one uppercase letter");
            }

            if (!preg_match("/[a-z]/", $password)) {
                throw new Exception("Password must contain at least one lowercase letter");
            }

            if (!preg_match("/[0-9]/", $password)) {
                throw new Exception("Password must contain at least one number");
            }

            if (!preg_match("/[\W]/", $password)) {
                throw new Exception("Password must contain at least one special character");
            }

            return [
                "status" => true,
                "data" => $password
            ];
        } catch (Exception $e) {
            return [
                "status" => false,
                "message" => $e->getMessage()
            ];
        }
    }

    public static function phone($phone)
    {
        $phone = trim($phone);

        try {
            if (empty($phone)) {
                throw new Exception("Phone number is required");
            }

            if (!preg_match("/^[6-9][0-9]{9}$/", $phone)) {
                throw new Exception("Invalid phone number");
            }

            return [
                "status" => true,
                "data" => $phone
            ];
        } catch (Exception $e) {
            return [
                "status" => false,
                "message" => $e->getMessage()
            ];
        }
    }

    public static function loginPassword($password)
    {
        $password = trim($password);

        try {
            if (empty($password)) {
                throw new Exception("Password is required");
            }

            return [
                "status" => true,
                "data" => $password
            ];
        } catch (Exception $e) {
            return [
                "status" => false,
                "message" => $e->getMessage()
            ];
        }
    }

    public static function courseName($name)
    {
        $name = trim($name);

        try {
            if (empty($name)) {
                throw new Exception("Course name is required");
            }

            if (strlen($name) < 3 || strlen($name) > 100) {
                throw new Exception("Course name must be between 3 and 100 characters");
            }

            return [
                "status" => true,
                "data" => htmlspecialchars($name)
            ];
        } catch (Exception $e) {
            return [
                "status" => false,
                "message" => $e->getMessage()
            ];
        }
    }

    public static function weeks($weeks)
    {
        $weeks = trim($weeks);

        try {
            if (empty($weeks)) {
                throw new Exception("Duration is required");
            }

            if (!filter_var($weeks, FILTER_VALIDATE_INT) || $weeks <= 0) {
                throw new Exception("Duration must be a positive integer");
            }

            return [
                "status" => true,
                "data" => (int)$weeks
            ];
        } catch (Exception $e) {
            return [
                "status" => false,
                "message" => $e->getMessage()
            ];
        }
    }

    public static function seats($seats)
    {
        $seats = trim($seats);

        try {
            if (empty($seats)) {
                throw new Exception("Seats are required");
            }

            if (!filter_var($seats, FILTER_VALIDATE_INT) || $seats <= 0) {
                throw new Exception("Seats must be a positive integer");
            }

            return [
                "status" => true,
                "data" => (int)$seats
            ];
        } catch (Exception $e) {
            return [
                "status" => false,
                "message" => $e->getMessage()
            ];
        }
    }
    public static function role($role)
    {
        $role = trim($role);

        try {
            if (empty($role)) {
                throw new Exception("Role is required");
            }

            $allowedRoles = ['Admin', 'Instructor'];

            if (!in_array($role, $allowedRoles)) {
                throw new Exception("Role must be either Admin or Instructor");
            }

            return [
                "status" => true,
                "data" => htmlspecialchars($role)
            ];
        } catch (Exception $e) {
            return [
                "status" => false,
                "message" => $e->getMessage()
            ];
        }
    }
}
