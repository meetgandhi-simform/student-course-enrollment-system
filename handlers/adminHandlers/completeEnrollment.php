<?php
session_start();
require_once __DIR__ . "/../../class/Enrollments.php";
require_once __DIR__ . "./../../helper/AuthHelper.php";

AuthHelper::requireLogin();
AuthHelper::requireRole(['admin', 'student']);

if (!isset($_GET['id']) || !is_numeric($_GET['id'])) {
    header("Location: /course-management/ui/admin/adminDashboard.php");
    exit();
}

$id = (int)$_GET['id'];

$obj = new Enrollments();
$result = $obj->completeEnrollment($id);

if ($result['status']) {
    echo "<script>window.history.back();</script>";
} else {
    $_SESSION['error'] = $result['message'];
    echo "<script>window.history.back();</script>";
}
exit();
