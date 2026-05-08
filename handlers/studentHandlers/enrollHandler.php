<?php

session_start();

header("Content-Type: application/json");

require_once __DIR__ . "/../../helper/AuthHelper.php";
require_once __DIR__ . "/../../class/Enrollments.php";

AuthHelper::requireLogin();

AuthHelper::requireRole('student');

if (
    !isset($_POST['course_instructor_id']) ||
    !isset($_POST['course_id']) ||
    !is_numeric($_POST['course_instructor_id']) ||
    !is_numeric($_POST['course_id'])
) {

    echo json_encode([
        'status' => false,
        'message' => 'Invalid Request'
    ]);

    exit();
}

$course_instructor_id = (int) $_POST['course_instructor_id'];

$course_id = (int) $_POST['course_id'];

$student_id = $_SESSION['user_id'];

$enrollObj = new Enrollments();

$response = $enrollObj->enrollStudent(
    $student_id,
    $course_instructor_id,
    $course_id
);

if ($response['status']) {

    echo json_encode([
        'status' => true,
        'message' => 'Enrolled Successfully'
    ]);

    exit();
}

echo json_encode([
    'status' => false,
    'message' => $response['message']
]);
