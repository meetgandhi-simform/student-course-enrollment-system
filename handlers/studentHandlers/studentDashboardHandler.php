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

$tab = $_GET['tab'] ?? 'enrollments';

$enroll_page = max(1, (int)($_GET['enroll_page'] ?? 1));
$course_page = max(1, (int)($_GET['course_page'] ?? 1));

$student_id = $_SESSION['user_id'];

if ($tab === 'enrollments') {
    $enrollResponse = $enrollObj->getEnrollmentsById($student_id, $enroll_page, $limit);

    if ($enrollResponse['status']) {
        $enrollments = $enrollResponse['data'];
    } else {
        $enrollments = [];
        $_SESSION['error'] = $enrollResponse['message'];
    }

    if ($enrollObj->countEnrollmentsByStudentId($student_id)) {
        $totalEnrollments = $enrollObj->countEnrollmentsByStudentId($student_id)['total'];
    } else {
        $totalEnrollments = [];
        $_SESSION['error'] = $enrollObj->countEnrollmentsByStudentId($student_id)['message'];
    }

    $totalEnrollmentPages = ceil($totalEnrollments / $limit);
}

if ($tab === 'courses') {
    $courseResponse = $courseObj->getAllCourses($student_id, $course_page, $limit);

    if ($courseResponse['status']) {
        $courses = $courseResponse['data'];
    } else {
        $courses = [];
        $_SESSION['error'] = $courseResponse['data'];
    }

    if ($courseObj->countAllCourses($student_id)) {
        $totalCourses = $courseObj->countAllCourses($student_id)['total'];
    } else {
        $totalCourses = [];
        $_SESSION['error'] = $courseObj->countAllCourses($student_id)['message'];
    }

    $totalCoursePages = ceil($totalCourses / $limit);
}

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
