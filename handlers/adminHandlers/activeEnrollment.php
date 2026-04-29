<?php
session_start();
require_once __DIR__ . "/../../class/Enrollments.php";
require_once __DIR__ . "./../../helper/AuthHelper.php";

// check
AuthHelper::requireRole('admin');

// Validate ID
if (!isset($_GET['id']) || !is_numeric($_GET['id'])) {
    header("Location: /course-management/ui/admin/adminDashboard.php");
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
