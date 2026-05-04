<?php

require_once __DIR__ . "/../helper/AuthHelper.php";
require_once __DIR__ . "/../auth/Login.php";
require_once __DIR__ . "/../validator/Validator.php";

session_start();

header('Content-Type: application/json');

if ($_SERVER['REQUEST_METHOD'] == 'POST') {

    $loginObj = new Login();

    $email = Validator::email($_POST['email']);
    $password = Validator::loginPassword($_POST['password']);

    $errors = [];

    if (!$email['status']) $errors[] = $email['message'];
    if (!$password['status']) $errors[] = $password['message'];


    if (!isset($_POST['captcha']) || !isset($_SESSION['captcha'])) {
        $errors[] = "Captcha missing!";
    } else {
        if ($_POST['captcha'] !== $_SESSION['captcha']) {
            $errors[] = "Invalid captcha!";
        }
    }

    unset($_SESSION['captcha']);

    if (!empty($errors)) {
        echo json_encode([
            "status" => false,
            "errors" => $errors
        ]);
        exit();
    }

    $user = $loginObj->login(
        $email['data'],
        $password['data']
    );

    if ($user) {
        if ($user['isActive'] !== 'Active') {
            echo json_encode([
                "status" => false,
                "message" => "Your account is inactive"
            ]);
            exit();
        }

        $_SESSION['user_id'] = $user['id'];
        $_SESSION['name'] = $user['name'];
        $_SESSION['role'] = $user['role'];
        $_SESSION['isActive'] = $user['isActive'];

        echo json_encode([
            "status" => true,
            "role" => strtolower($user['role'])
        ]);
        exit();
    } else {
        echo json_encode([
            "status" => false,
            "message" => "Invalid email or password"
        ]);
        exit();
    }
}

echo json_encode([
    "status" => false,
    "message" => "Invalid request"
]);
exit();
