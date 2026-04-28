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
$enrollment_page = max(1, (int)($_GET['enrollment_page'] ?? 1));

$totalAdmins = [];
$totalStudents = [];
$totalInstructors = [];
$totalCourses = [];
$totalEnrollments = [];
$totalActiveUsers = [];

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

    if ($instructorObj->countInstructor()['status']) {
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

    if ($userObj->countAdmins()['status']) {
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

    if ($courseObj->countCourseWithInstructor()['status']) {
        $totalCourseWithInstructor = $courseObj->countCourseWithInstructor()['count'];
    } else {
        $totalCourseWithInstructor = [];
        $_SESSION['error'] = $courseObj->countCourseWithInstructor()['message'];
    }

    $totalCourseWithInstructorPages = ceil($totalCourseWithInstructor / $limit);
}

if ($tab === 'enrollments') {
    $enrollmentResponse = $enrollObj->allEnrollments($enrollment_page, $limit);

    if ($enrollmentResponse['status']) {
        $enrollments = $enrollmentResponse['data'];
    } else {
        $enrollments = [];
        $_SESSION['error'] = $enrollmentResponse['message'];
    }

    if ($enrollObj->countEnrollments()['status']) {
        $totalEnrollments = $enrollObj->countEnrollments()['count'];
    } else {
        $totalEnrollments = [];
        $_SESSION['error'] = $enrollObj->countEnrollments()['message'];
    }

    $totalEnrollmentPages = ceil($totalEnrollments / $limit);
}

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
