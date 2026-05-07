<?php
session_start();

require_once __DIR__ . "./../../class/User.php";
require_once __DIR__ . "./../../class/Course.php";
require_once __DIR__ . "./../../class/Instructor.php";
require_once __DIR__ . "./../../helper/AuthHelper.php";
require_once __DIR__ . "./../../class/Enrollments.php";

AuthHelper::requireLogin();
AuthHelper::requireRole('admin');

$userObj = new User();
$courseObj = new Course();
$instructorObj = new Instructor();
$enrollObj = new Enrollments();

if ($userObj->countAdmins()['status']) {
    $totalAdmins = $userObj->countAdmins()['count'];
} else {
    $totalAdmins = [];
    $_SESSION['error'] = $userObj->countAdmins()['message'];
}

if ($userObj->countStudents()['status']) {
    $totalStudents = $userObj->countStudents()['count'];
} else {
    $totalStudents = [];
    $_SESSION['error'] = $userObj->countStudents()['message'];
}

if ($instructorObj->countInstructor()['status']) {
    $totalInstructors = $instructorObj->countInstructor()['count'];
} else {
    $totalInstructors = [];
    $_SESSION['error'] = $instructorObj->countInstructor()['message'];
}

if ($courseObj->countCourses()['status']) {
    $totalCourses = $courseObj->countCourses()['count'];
} else {
    $totalCourses = [];
    $_SESSION['error'] = $courseObj->countCourses()['message'];
}

if ($userObj->countActiveUsers()['status']) {
    $totalActiveUsers = $userObj->countActiveUsers()['count'];
} else {
    $totalActiveUsers = [];
    $_SESSION['error'] = $userObj->countActiveUsers()['message'];
}
if ($enrollObj->countEnrollments()['status']) {
    $totalEnrollments = $enrollObj->countEnrollments()['count'];
} else {
    $totalEnrollments = [];
    $_SESSION['error'] = $enrollObj->countEnrollments()['message'];
}
