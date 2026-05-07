<?php
session_start();

header("Content-Type: application/json");

require_once __DIR__ . "/../../class/Enrollments.php";
require_once __DIR__ . "/../../helper/AuthHelper.php";

AuthHelper::requireRole(['admin', 'student']);

if (!isset($_POST['id']) || !is_numeric($_POST['id'])) {

    echo json_encode([
        'status' => false,
        'message' => 'Invalid Enrollment ID'
    ]);

    exit();
}


$enrollment_id = (int) $_POST['id'];

$obj = new Enrollments();

$result = $obj->deleteEnrollment($enrollment_id);

if ($result['status']) {

    echo json_encode([
        'status' => true,
        'message' => 'Enrollment cancelled successfully'
    ]);

    exit();
}

echo json_encode([
    'status' => false,
    'message' => $result['message']
]);
