<?php
session_start();
require_once __DIR__ . "/../../class/User.php";
require_once __DIR__ . "./../../helper/AuthHelper.php";

// check
AuthHelper::requireRole(['admin', 'instructor']);

// Validate ID
if (!isset($_GET['id']) || !is_numeric($_GET['id'])) {
    header("Location: /course-management/ui/admin/adminDashboard.php");
    exit();
}

$id = (int)$_GET['id'];

$userObj = new User();
if ($userObj->activateUser($id)['status'] !== false) {
    $result = $userObj->activateUser($id);
    if ($result) {
        echo "<script>window.history.back();</script>";
    } else {
        $_SESSION['error'] = $userObj->activateUser($id)['Message'];
        echo "<script>window.history.back();</script>";
    }
}
exit();
