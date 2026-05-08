<?php

session_start();

header("Content-Type: application/json");

require_once __DIR__ . "/../../helper/AuthHelper.php";
require_once __DIR__ . "/../../class/Enrollments.php";

AuthHelper::requireLogin();

AuthHelper::requireRole('student');

$enrollObj = new Enrollments();

$student_id = $_SESSION['user_id'];

$response = $enrollObj->getEnrollmentsById(
    $student_id,
    1,
    1000
);

echo json_encode($response);
