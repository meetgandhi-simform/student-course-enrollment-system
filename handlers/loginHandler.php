<?php

require_once __DIR__ . "/../helper/AuthHelper.php";
require_once __DIR__ . "/../auth/Login.php";
require_once __DIR__ . "/../validator/Validator.php";

session_start();

if ($_SERVER['REQUEST_METHOD'] == 'POST') {

    $loginObj = new Login();

    $email = Validator::email($_POST['email']);
    $password = Validator::loginPassword($_POST['password']);

    $errors = [];

    if (!$email['status']) $errors[] = $email['message'];
    if (!$password['status']) $errors[] = $password['message'];

    // ✅ CAPTCHA VALIDATION
    if (!isset($_POST['captcha']) || !isset($_SESSION['captcha'])) {
        $errors[] = "Captcha missing!";
        
    } else {
        if ($_POST['captcha'] !== $_SESSION['captcha']) {
            $errors[] = "Invalid captcha!";
        }
    }

    // destroy captcha after check (important)
    unset($_SESSION['captcha']);

    if (!empty($errors)) {
        $allErrors = implode("\n", $errors);

        echo "<script>
            alert(" . json_encode($allErrors) . ");
            window.location.href = '/course-management/ui/login.php';
        </script>";
        exit();
    }

    // ✅ Proceed only if captcha + validation passed
    $user = $loginObj->login(
        $email['data'],
        $password['data']
    );

    if ($user) {
        $_SESSION['user_id'] = $user['id'];
        $_SESSION['name'] = $user['name'];
        $_SESSION['role'] = $user['role'];
        $_SESSION['isActive'] = $user['isActive'];

        if ($user['isActive'] === 'Active') {
            AuthHelper::redirectUser(strtolower($user['role']));
        } else {
            echo "<script>
                alert('Your Account is Deactivated!!');
                window.history.back();
            </script>";
        }
    } else {
        echo "<script>
            alert('Invalid Email or Password');
            window.location.href = '/course-management/ui/login.php';
        </script>";
        exit();
    }
}
?>