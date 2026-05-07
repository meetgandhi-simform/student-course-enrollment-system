<?php
session_start();

header("Content-Type: application/json");

require_once __DIR__ . "/../../class/User.php";
require_once __DIR__ . "./../../helper/AuthHelper.php";

// check
AuthHelper::requireRole(['admin', 'instructor']);

// Validate ID
if (!isset($_POST['id']) || !is_numeric($_POST['id'])) {
    echo json_encode([
        'status' => false,
        'message' => 'Invalid User ID'
    ]);

    exit();
}

$id = (int) $_POST['id'];

$userObj = new User();

$result = $userObj->activateUser($id);

if ($result['status']) {

    echo json_encode([
        'status' => true,
        'message' => 'User activated successfully'
    ]);

    exit();
}

echo json_encode([
    'status' => false,
    'message' => $result['message']
]);
