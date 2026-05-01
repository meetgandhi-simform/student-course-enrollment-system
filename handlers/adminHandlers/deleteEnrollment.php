<?php
session_start();

require_once __DIR__ . "/../../class/Enrollments.php";
require_once __DIR__ . "/../../helper/AuthHelper.php";

AuthHelper::requireRole(['admin', 'student']);

if (!isset($_GET['id']) || !is_numeric($_GET['id'])) {
    header("Location: /course-management/ui/admin/adminDashboard.php");
    exit();
}


$enrollment_id = (int) $_GET['id'];

$obj = new Enrollments();

$result = $obj->deleteEnrollment($enrollment_id);

if ($result['status'] === true) {
    echo "<script>
        window.history.back();
    </script>";
} else {
    $_SESSION['error'] = $result['message'];
    echo "<script>window.history.back();</script>";
}

exit();
