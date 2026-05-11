<?php

session_start();

header("Content-Type: application/json");

require_once __DIR__ . "/../../class/Instructor.php";
require_once __DIR__ . "/../../helper/AuthHelper.php";

AuthHelper::requireRole(['admin', 'instructor']);

$instructorObj = new Instructor();

$draw = $_POST['draw'] ?? 1;

$start = $_POST['start'] ?? 0;

$length = $_POST['length'] ?? 5;

$search = $_POST['search']['value'] ?? '';

$orderColumnIndex = $_POST['order'][0]['column'] ?? 0;

$orderDirection = $_POST['order'][0]['dir'] ?? 'asc';

$columns = [
    0 => 'id',
    1 => 'name',
    2 => 'email',
    3 => 'phone',
    4 => 'role',
    5 => 'isActive'
];

$orderColumn = $columns[$orderColumnIndex];

$response = $instructorObj->getInstructorsServerSide($start, $length, $search, $orderColumn, $orderDirection, $draw);

echo json_encode($response);
