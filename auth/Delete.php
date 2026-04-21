<?php
session_start();
require_once "/var/www/html/course-management/Class/User.php";

// check
if (!isset($_SESSION['user_id']) || strtolower($_SESSION['role']) != 'admin') {
    header("Location: /course-management/ui/login.php");
    exit();
}

// Validate ID
if (!isset($_GET['id']) || !is_numeric($_GET['id'])) {
    header("Location: /course-management/ui/admin_dashboard.php");
    exit();
}

$id = (int)$_GET['id'];

$userObj = new User();
if ($userObj->deleteUsers($id)['status'] !== false) {
    $result = $userObj->deleteUsers($id);

    if ($result) {
        echo "<script>window.history.back();</script>";
    } else {
        $_SESSION['error'] = $userObj->activateUser($id)['Message'];
        echo "<script>window.history.back();</script>";
    }
}
exit();
?>