<?php

session_start();

header("Content-Type: application/json");

require_once __DIR__ . "/../../helper/AuthHelper.php";
require_once __DIR__ . "/../../class/Course.php";

AuthHelper::requireLogin();

AuthHelper::requireRole('student');

$courseObj = new Course();

$student_id = $_SESSION['user_id'];

$response = $courseObj->getAllCourses(
    $student_id,
    1,
    1000
);

echo json_encode($response);
