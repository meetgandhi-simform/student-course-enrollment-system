<?php

session_start();

header("Content-Type: application/json");

require_once __DIR__ . "/../../helper/AuthHelper.php";
require_once __DIR__ . "/../../class/User.php";

AuthHelper::requireLogin();
AuthHelper::requireRole('instructor');

$userObj = new User();
$instructor_id = $_SESSION['user_id'];
$draw = $_POST['draw'] ?? 1;
$start = $_POST['start'] ?? 0;
$length = $_POST['length'] ?? 5;
$search = $_POST['search']['value'] ?? '';
$orderColumnIndex = $_POST['order'][0]['column'] ?? 0;
$orderDirection = $_POST['order'][0]['dir'] ?? 'asc';

$columns = [
    0 => 'us.id',
    1 => 'us.name',
    2 => 'us.email',
    3 => 'us.phone',
    4 => 'c.course_name',
    5 => 'us.isActive'
];

$orderColumn = $columns[$orderColumnIndex];
$response = $userObj->getStudentsByInstructorServerSide(
    $draw,
    $instructor_id,
    $start,
    $length,
    $search,
    $orderColumn,
    $orderDirection
);

echo json_encode($response);
