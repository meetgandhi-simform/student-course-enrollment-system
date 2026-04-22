<?php
session_start();
require_once __DIR__ . "./../Class/Course.php";
require_once __DIR__ . "./../helper/redirectUser.php";

// auth check
requireRole('admin');

// validate inputs
if (
    !isset($_GET['course_id'], $_GET['instructor_id']) ||
    !is_numeric($_GET['course_id']) ||
    !is_numeric($_GET['instructor_id'])
) {

    header("Location: /course-management/ui/admin_dashboard.php");
    exit();
}

$course_id = (int)$_GET['course_id'];
$instructor_id = (int)$_GET['instructor_id'];

$obj = new Course();
$result = $obj->deleteCourseInstructor($course_id, $instructor_id);

if ($result['status'] !== false) {
    echo "<script>window.history.back();</script>";
}else{
    $_SESSION['error'] = $result['message'];
    echo "<script>window.history.back();</script>";

}

header("Location: /course-management/ui/admin_dashboard.php");
exit();
?>