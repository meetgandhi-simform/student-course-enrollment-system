<?php

session_start();

header("Content-Type: application/json");

require_once __DIR__ . "/../../class/Course.php";
require_once __DIR__ . "/../../helper/AuthHelper.php";

AuthHelper::requireRole('admin');

$courseObj = new Course();

$response = $courseObj->courseWithInstructor(1, 1000);

echo json_encode($response);
