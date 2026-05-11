<?php

session_start();

header("Content-Type: application/json");

require_once __DIR__ . "/../../class/Enrollments.php";
require_once __DIR__ . "/../../helper/AuthHelper.php";

AuthHelper::requireRole(['admin', 'instructor']);

$enrollObj = new Enrollments();

$draw = $_POST['draw'] ?? 1;

$start = $_POST['start'] ?? 0;

$length = $_POST['length'] ?? 5;

$search = $_POST['search']['value'] ?? '';

$orderColumnIndex = $_POST['order'][0]['column'] ?? 0;

$orderDirection = $_POST['order'][0]['dir'] ?? 'asc';

$columns = [

    0 => 'e.id',
    1 => 'c.course_name',
    2 => 's.id',
    3 => 's.name',
    4 => 'i.id',
    5 => 'i.name',
    6 => 'e.status'
];

$orderColumn = $columns[$orderColumnIndex];

$response = $enrollObj->getEnrollmentsServerSide($start, $length, $search, $orderColumn, $orderDirection, $draw);

echo json_encode($response);
