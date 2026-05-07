<?php

session_start();

header("Content-Type: application/json");

require_once __DIR__ . "/../../class/Enrollments.php";
require_once __DIR__ . "/../../helper/AuthHelper.php";

AuthHelper::requireRole(['admin', 'instructor']);

$enrollObj = new Enrollments();

$response = $enrollObj->allEnrollments(1, 1000);

echo json_encode($response);
