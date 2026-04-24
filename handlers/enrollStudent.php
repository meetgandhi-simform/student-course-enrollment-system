<?php

session_start();

require_once __DIR__ . "/./../helper/auth.php";
require_once __DIR__ . "/./../Class/Enrollments.php";

requireRole('admin');

if ($_SERVER['REQUEST_METHOD'] === 'POST') {

    if (!isset($_POST['student_id'], $_POST['course_instructor_id'])) {
        $_SESSION['error'] = "Invalid Request!!";
        header("Location: /course-management/ui/enroll_student.php");
        exit();
    }

    $student_id = (int) $_POST['student_id'];
    $course_instructor_id = (int) $_POST['course_instructor_id'];

    if ($student_id <= 0 || $course_instructor_id <= 0) {
        $_SESSION['error'] = "Invalid data!";
        header("Location: /course-management/ui/enroll_student.php");
        exit();
    }

    $obj = new Enrollments();
    $result = $obj->enrollStudent($student_id, $course_instructor_id);

    if ($result['status']) {
            echo "<script>
                alert('Student enrolled successfully!')
                window.location.href = '/course-management/ui/allEnrollments.php?tab=enrollments';
            </script>";
    } else {
        $_SESSION['error'] = $result['message'];
    }

    header("Location: /course-management/ui/enroll_student.php");
    exit();
}

$course_id = $_GET['course_id'];

$enrollobj = new Enrollments();

$result = $enrollobj->getInstructorPerCourse($course_id);

if ($result['status'] === true) {
    $instructor = $result['data'];

    header("Content-Type: application/json");
    echo json_encode($instructor);
    exit;
}


