<?php

session_start();

require_once __DIR__ . "/../../class/User.php";
require_once __DIR__ . "/../../helper/AuthHelper.php";

header("Content-Type: application/json");

AuthHelper::requireLogin();
AuthHelper::requireRole('admin');

$userObj = new User();

$response = $userObj->totalAdmins(1, 1000);

echo json_encode($response);
