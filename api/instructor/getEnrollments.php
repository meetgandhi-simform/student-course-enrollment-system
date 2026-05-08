<?php

session_start();

header("Content-Type: application/json");

require_once __DIR__ . "/../../helper/AuthHelper.php";
require_once __DIR__ . "/../../class/User.php";

AuthHelper::requireLogin();

AuthHelper::requireRole('instructor');

$userObj = new User();

$instructor_id = $_SESSION['user_id'];

$response = $userObj->getStudentsByInstructor(
    $instructor_id,
    1,
    1000
);

echo json_encode($response);
