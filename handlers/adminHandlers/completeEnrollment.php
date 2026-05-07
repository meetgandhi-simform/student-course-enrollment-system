<?php

session_start();

header("Content-Type: application/json");

require_once __DIR__ . "/../../class/Enrollments.php";
require_once __DIR__ . "/../../helper/AuthHelper.php";

AuthHelper::requireLogin();

AuthHelper::requireRole(['admin', 'student']);

if (!isset($_POST['id']) || !is_numeric($_POST['id'])) {

    echo json_encode([
        'status' => false,
        'message' => 'Invalid Enrollment ID'
    ]);

    exit();
}

$id = (int) $_POST['id'];

$obj = new Enrollments();

$result = $obj->completeEnrollment($id);

if ($result['status']) {

    echo json_encode([
        'status' => true,
        'message' => 'Enrollment completed successfully'
    ]);

    exit();
}

echo json_encode([
    'status' => false,
    'message' => $result['message']
]);
