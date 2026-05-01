<?php
session_start();

require_once __DIR__ . "./../../helper/AuthHelper.php";
require_once __DIR__ . "/../../class/Enrollments.php";
require __DIR__ . "./../../class/Course.php";

AuthHelper::requireLogin();
AuthHelper::requireRole('student');

$enrollObj = new Enrollments();
$courseObj = new Course();

if (
    !isset($_GET['course_instructor_id']) ||
    !isset($_GET['course_id']) ||
    !is_numeric($_GET['course_instructor_id']) ||
    !is_numeric($_GET['course_id'])
) {
    $_SESSION['error'] = "Invalid request!";
    header("Location: /course-management/ui/student/allCourses.php");
    exit();
}

$course_instructor_id = $_GET['course_instructor_id'];
$course_id = $_GET['course_id'];
$student_id = $_SESSION['user_id'];

$response = $enrollObj->enrollStudent($student_id, $course_instructor_id, $course_id);

if ($response['status']) {
    echo "<script>
            alert('Enrolled Sucessfully');
            window.location.href = '/course-management/ui/student/allEnrollments.php?tab=enrollments';
        </script>";
} else {
    $_SESSION['error'] = $response['message'];
}

header("Location: /course-management/ui/student/allCourses.php");
exit();
