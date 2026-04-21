<?php
session_start();

require_once '/var/www/html/course-management/Class/User.php';
require_once '/var/www/html/course-management/validator/Validator.php';
require_once '/var/www/html/course-management/helper/auth.php';

requireLogin();
requireRole('admin');

if ($_SERVER['REQUEST_METHOD'] == 'POST') {

    $userObj = new User();

    $name = Validator::name($_POST['name']);
    $email = Validator::email($_POST['email']);
    $password = Validator::password($_POST['password']);
    $phone = Validator::phone($_POST['phone']);
    $role = $_POST['role']; 

    $errors = [];

    if (!$name['status']) $errors[] = $name['message'];
    if (!$email['status']) $errors[] = $email['message'];
    if (!$password['status']) $errors[] = $password['message'];
    if (!$phone['status']) $errors[] = $phone['message'];


    if (!empty($errors)) {
        $allErrors = implode("\n", $errors);

        echo "<script>
        alert(" . json_encode($allErrors) . ");
        window.location.href = '/course-management/ui/admin_create_user.php';
    </script>";
        exit();
    }

    try{
    $result = $userObj->createUser(
        $name['data'],
        $email['data'],
        $password['data'],
        $phone['data'],
        $role
    );
    }catch(Exception $e){
        echo "<script>
            alert('Error: ".htmlspecialchars($e->getMessage()). "');
            window.location.href = '/course-management/ui/admin_dashboard.php';
        </script>";
    }
    if ($result) {
        echo "<script>
            alert('User Created Successfully');
            window.location.href = '/course-management/ui/admin_dashboard.php';
        </script>";
    } else {
        echo "<script>
            alert('Error creating user');
            window.location.href = '/course-management/ui/admin_dashboard.php';
        </script>";
    }
}
?>