<?php

session_start();

require_once __DIR__ . "/../../helper/AuthHelper.php";
require_once __DIR__ . "/../../class/Enrollments.php";
require_once __DIR__ . "/../../class/Course.php";

AuthHelper::requireRole('admin');

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    $_SESSION['error'] = "Invalid request!";
    header("Location: /course-management/ui/admin/enrollStudent.php");
    exit();
}

// Validate input
if (!isset($_POST['student_id'], $_POST['course_instructor_id'], $_POST['course_id'])) {
    $_SESSION['error'] = "Invalid Request!";
    header("Location: //course-management/ui/admin/enrollStudent.php");
    exit();
}

$student_id = (int) $_POST['student_id'];
$course_instructor_id = (int) $_POST['course_instructor_id'];
$course_id = (int) $_POST['course_id'];

if ($student_id <= 0 || $course_instructor_id <= 0 || $course_id <= 0) {
    $_SESSION['error'] = "Invalid data!";
    header("Location: /course-management/ui/admin/enrollStudent.php");
    exit();
}

$enrollObj = new Enrollments();
$courseObj = new Course();


$course = $courseObj->getCourseById($course_id);

if (!$course['status']) {
    $_SESSION['error'] = "Course not found!";
    header("Location: /course-management/ui/admin/enrollStudent.php");
    exit();
}

if ($course['data']['avail_seats'] <= 0) {
    $_SESSION['error'] = "No seats available for this course!";
    header("Location: /course-management/ui/admin/enrollStudent.php");
    exit();
}


$result = $enrollObj->enrollStudent($student_id, $course_instructor_id);

if (!$result['status']) {
    $_SESSION['error'] = $result['message'];
    header("Location: /course-management/ui/admin/enrollStudent.php");
    exit();
}

$courseResult = $courseObj->updateSeats($course_id);

if (!$courseResult['status']) {
    $_SESSION['error'] = $courseResult['message'];
    header("Location: /course-management/ui/admin/enrollStudent.php");
    exit();
}


$_SESSION['success'] = "Student enrolled successfully!";
header("Location: /course-management/ui/admin/allEnrollments.php?tab=enrollments");
exit();
?>