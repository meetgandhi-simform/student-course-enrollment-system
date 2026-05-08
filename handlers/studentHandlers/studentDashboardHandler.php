<?php
session_start();

require_once __DIR__ . "./../../helper/AuthHelper.php";
require_once __DIR__ . "./../../class/Enrollments.php";
require_once __DIR__ . "./../../class/Course.php";

AuthHelper::requireLogin();
AuthHelper::requireRole('student');

$enrollObj = new Enrollments();
$courseObj = new Course();

$limit = 10;

$student_id = $_SESSION['user_id'];

if ($enrollObj->countEnrollmentsByStudentId($student_id)) {
    $totalEnrollments = $enrollObj->countEnrollmentsByStudentId($student_id)['total'];
} else {
    $totalEnrollments = [];
    $_SESSION['error'] = $enrollObj->countEnrollmentsByStudentId($student_id)['message'];
}

if ($courseObj->countAllCourses($student_id)) {
    $totalCourses = $courseObj->countAllCourses($student_id)['total'];
} else {
    $totalCourses = [];
    $_SESSION['error'] = $courseObj->countAllCourses($student_id)['message'];
}
