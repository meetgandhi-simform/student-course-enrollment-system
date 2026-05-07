<?php

session_start();

header("Content-Type: application/json");

require_once __DIR__ . "/../../class/Course.php";
require_once __DIR__ . "/../../helper/AuthHelper.php";

AuthHelper::requireRole('admin');

if (
    !isset($_POST['course_id'], $_POST['instructor_id']) ||
    !is_numeric($_POST['course_id']) ||
    !is_numeric($_POST['instructor_id'])
) {

    echo json_encode([
        'status' => false,
        'message' => 'Invalid Input'
    ]);

    exit();
}

$course_id = (int) $_POST['course_id'];

$instructor_id = (int) $_POST['instructor_id'];

$obj = new Course();

$result = $obj->deleteCourseInstructor(
    $course_id,
    $instructor_id
);

if ($result['status']) {

    echo json_encode([
        'status' => true,
        'message' => 'Instructor removed successfully'
    ]);

    exit();
}

echo json_encode([
    'status' => false,
    'message' => $result['message']
]);
