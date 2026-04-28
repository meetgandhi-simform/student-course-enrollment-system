<?php
session_start();
require_once __DIR__ . "/../Class/Enrollments.php";
require_once __DIR__ . "./../helper/auth.php";

// check
requireRole('admin');

// Validate ID
if (!isset($_GET['id']) || !is_numeric($_GET['id'])) {
    header("Location: /course-management/ui/admin_dashboard.php");
    exit();
}

$id = (int)$_GET['id'];

$obj = new Enrollments();
if ($obj->activeEnrollment($id)['status'] === true) {
    $result = $obj->activeEnrollment($id);
    if ($result) {
        echo "<script>window.history.back();</script>";
    } else {
        $_SESSION['error'] = $userObj->activateUser($id)['Message'];
        echo "<script>window.history.back();</script>";
    }
}
exit();
