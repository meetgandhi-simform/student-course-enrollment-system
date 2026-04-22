<?php
session_start();

require_once __DIR__ . "./../Class/User.php";
require_once __DIR__ . "./../Class/Course.php";
require_once __DIR__ . "./../Class/Instructor.php";
require_once __DIR__ . "./../helper/auth.php";
require_once __DIR__ . "./../Class/Enrollments.php";

requireLogin();
requireRole('admin');

$userObj = new User();
$courseObj = new Course();
$instructorObj = new Instructor();
$enrollObj = new Enrollments();

$limit = 10;

$tab = $_GET['tab'] ?? 'students';

$student_page = max(1, (int)($_GET['student_page'] ?? 1));
$instructor_page = max(1, (int)($_GET['instructor_page'] ?? 1));
$course_page = max(1, (int)($_GET['course_page'] ?? 1));
$admin_page = max(1, (int)($_GET['admin_page'] ?? 1));


if ($tab === 'students') {
    $studentResponse = $userObj->totalStudents($student_page, $limit);

    if ($studentResponse['status']) {
        $students = $studentResponse['data'];
    } else {
        $students = [];
        $_SESSION['error'] = $studentResponse['message'];
    }

    if ($userObj->countStudents()['status']) {
        $totalStudents = $userObj->countStudents()['count'];
    } else {
        $totalStudents = [];
        $_SESSION['error'] = $userObj->countStudents()['message'];
    }

    $totalStudentPages = ceil($totalStudents / $limit);
}

if ($tab === 'instructors') {
    $instructorResponse = $instructorObj->totalInstructors($instructor_page, $limit);

    if ($instructorResponse['status']) {
        $instructors = $instructorResponse['data'];
    } else {
        $instructors = [];
        $_SESSION['error'] = $instructorResponse['message'];
    }

    if ($instructorObj->countInstructor()['status'] !== false) {
        $totalInstructors = $instructorObj->countInstructor()['count'];
    } else {
        $totalInstructors = [];
        $_SESSION['error'] = $instructorObj->countInstructor()['count'];
    }

    $totalInstructorPages = ceil($totalInstructors / $limit);
}


if ($tab === 'admins') {
    $adminResponse = $userObj->totalAdmins($admin_page, $limit);

    if ($adminResponse['status']) {
        $admins = $adminResponse['data'];
    } else {
        $admmins = [];
        $_SESSION['error'] = $adminResponse['message'];
    }

    if ($userObj->countAdmins()['status'] !== false) {
        $totalAdmins = $userObj->countAdmins()['count'];
    } else {
        $totalAdmins = [];
        $_SESSION['error'] = $userObj->countAdmins()['message'];
    }

    $totalAdminPages = ceil($totalAdmins / $limit);
}


if ($tab === 'courses') {
    $courseWithInstructorResponse = $courseObj->courseWithInstructor($course_page, $limit);

    if ($courseWithInstructorResponse['status']) {
        $courseWithInstructors = $courseWithInstructorResponse['data'];
    } else {
        $courseWithInstructors = [];
        $_SESSION['error'] = $courseWithInstructorResponse['message'];
    }

    if ($courseObj->countCourseWithInstructor()['status'] !== false) {
        $totalCourseWithInstructor = $courseObj->countCourseWithInstructor()['count'];
    } else {
        $totalCourseWithInstructor = [];
        $_SESSION['error'] = $courseObj->countCourseWithInstructor()['message'];
    }

    $totalCourseWithInstructorPages = ceil($totalCourseWithInstructor / $limit);
}

if ($userObj->countAdmins()['status'] !== false) {
    $totalAdmins = $userObj->countAdmins()['count'];
} else {
    $totalAdmins = [];
    $_SESSION['error'] = $userObj->countAdmins()['message'];
}

if ($userObj->countStudents()['status'] !== false) {
    $totalStudents = $userObj->countStudents()['count'];
} else {
    $totalStudents = [];
    $_SESSION['error'] = $userObj->countStudents()['message'];
}

if ($instructorObj->countInstructor()['status'] !== false) {
    $totalInstructors = $instructorObj->countInstructor()['count'];
} else {
    $totalInstructors = [];
    $_SESSION['error'] = $instructorObj->countInstructor()['message'];
}

if ($courseObj->countCourses()['status'] !== false) {
    $totalCourses = $courseObj->countCourses()['count'];
} else {
    $totalCourses = [];
    $_SESSION['error'] = $courseObj->countCourses()['message'];
}

if ($enrollObj->countEnrollments()['status'] !== false) {
    $totalEnrollments = $enrollObj->countEnrollments()['count'];
} else {
    $totalEnrollments = [];
    $_SESSION['error'] = $enrollObj->countEnrollments()['message'];
}
if ($userObj->countActiveUsers()['status'] !== false) {
    $totalActiveUsers = $userObj->countActiveUsers()['count'];
} else {
    $totalActiveUsers = [];
    $_SESSION['error'] = $userObj->countActiveUsers()['message'];
}
