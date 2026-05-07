<?php

session_start();

header("Content-Type: application/json");

require_once __DIR__ . "/../../class/User.php";
require_once __DIR__ . "/../../helper/AuthHelper.php";

AuthHelper::requireRole(['admin', 'instructor']);

$userObj = new User();

$response = $userObj->totalStudents(1, 1000);

echo json_encode($response);
