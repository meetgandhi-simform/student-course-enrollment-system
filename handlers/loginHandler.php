<?php

require_once __DIR__ . "./../helper/redirectUser.php";
require_once __DIR__ . "./../auth/Login.php";
require_once __DIR__ . "./../validator/Validator.php";


session_start();


// if (isset($_SESSION['user_id'])) {
//     $role = strtolower($_SESSION['role']);
//     redirectUser($role);
// }

if ($_SERVER['REQUEST_METHOD'] == 'POST') {

    $loginObj = new Login();

    $email = Validator::email($_POST['email']);
    $password = Validator::loginPassword($_POST['password']);

    $errors = [];

    if (!$email['status']) $errors[] = $email['message'];
    if (!$password['status']) $errors[] = $password['message'];

    if (!empty($errors)) {
        $allErrors = implode("\n", $errors);

        echo "<script>
            alert(" . json_encode($allErrors) . ");
            window.location.href = '/course-management/ui/login.php';
        </script>";
        exit();
    }

    $user = $loginObj->login(
        $email['data'],
        $password['data']
    );

    if ($user) {
        $_SESSION['user_id'] = $user['id'];
        $_SESSION['name'] = $user['name'];
        $_SESSION['role'] = $user['role'];

        redirectUser(strtolower($user['role']));
    } else {
        echo "<script>
            alert('Invalid Email or Password');
            window.location.href = '/course-management/ui/login.php';
        </script>";
        exit();
    }
}
