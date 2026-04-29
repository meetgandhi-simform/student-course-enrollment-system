<?php
session_start();

require_once __DIR__ . "./../../helper/AuthHelper.php";
require __DIR__ . "./../../class/User.php";
require __DIR__ . "./../../class/Course.php";

AuthHelper::requireLogin();
AuthHelper::requireRole('admin');

$userObj = new User();
$courseObj = new Course();

$limit = 10;

$tab = $_GET['tab'] ?? 'enrollments';

$student_page = max(1, (int)($_GET['student_page'] ?? 1));
$course_page = max(1, (int)($_GET['course_page'] ?? 1));


$instructor_id = $_SESSION['user_id'];

if ($tab === 'enrollments') {
    $studentResponse = $userObj->getStudentsByInstructor($instructor_id, $student_page, $limit);

    if ($studentResponse['status']) {
        $students = $studentResponse['data'];
    } else {
        $students = [];
        $_SESSION['error'] = $studentResponse['message'];
    }

    if ($userObj->countStudentsByInstructor($instructor_id)['status']) {
        $totalStudents = $userObj->countStudentsByInstructor($instructor_id)['total'];
    } else {
        $totalStudents = [];
        $_SESSION['error'] = $userObj->countStudentsByInstructor($instructor_id)['message'];
    }

    $totalStudentPages = ceil($totalStudents / $limit);
}

if ($tab === 'courses') {
    $courses = $courseObj->getCourseByInstructor($instructor_id, $course_page, $limit);

    if ($courses['status']) {
        $courseWithInstructors = $courses['data'];
    } else {
        $courseWithInstructors = [];
    }

    if ($courseObj->countCourseByInstructor($instructor_id)['count']) {
        $totalCourseWithInstructor = $courseObj->countCourseByInstructor($instructor_id)['count'];
    } else {
        $totalCourseWithInstructor = [];
        $_SESSION['error'] = $courseObj->countCourseByInstructor($instructor_id)['message'];
    }
    $totalCourseWithInstructorPages = ceil($totalCourseWithInstructor / $limit);
}

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
