<?php

session_start();

header("Content-Type: application/json");

require_once __DIR__ . "/../../class/Instructor.php";
require_once __DIR__ . "/../../helper/AuthHelper.php";

AuthHelper::requireRole(['admin', 'instructor']);

$instructorObj = new Instructor();

$response = $instructorObj->totalInstructors(1, 1000);

echo json_encode($response);
