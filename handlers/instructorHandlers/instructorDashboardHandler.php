<?php
session_start();

require_once __DIR__ . "./../../helper/AuthHelper.php";
require __DIR__ . "./../../class/User.php";
require __DIR__ . "./../../class/Course.php";

AuthHelper::requireLogin();
AuthHelper::requireRole('instructor');

$userObj = new User();
$courseObj = new Course();

$instructor_id = $_SESSION['user_id'];

if ($userObj->countStudentsByInstructor($instructor_id)['status']) {
    $totalStudents = $userObj->countStudentsByInstructor($instructor_id)['total'];
} else {
    $totalStudents = [];
    $_SESSION['error'] = $userObj->countStudentsByInstructor($instructor_id)['message'];
}

if ($courseObj->countCourseByInstructor($instructor_id)['count']) {
    $totalCourseWithInstructor = $courseObj->countCourseByInstructor($instructor_id)['count'];
} else {
    $totalCourseWithInstructor = [];
    $_SESSION['error'] = $courseObj->countCourseByInstructor($instructor_id)['message'];
}
