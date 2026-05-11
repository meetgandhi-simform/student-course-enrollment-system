<?php

session_start();

header("Content-Type: application/json");

require_once __DIR__ . "/../../helper/AuthHelper.php";
require_once __DIR__ . "/../../class/Course.php";

AuthHelper::requireLogin();

AuthHelper::requireRole('instructor');

$courseObj = new Course();

$instructor_id = $_SESSION['user_id'];

$draw = $_POST['draw'] ?? 1;

$start = $_POST['start'] ?? 0;

$length = $_POST['length'] ?? 5;

$search = $_POST['search']['value'] ?? '';

$orderColumnIndex = $_POST['order'][0]['column'] ?? 0;

$orderDirection = $_POST['order'][0]['dir'] ?? 'asc';

$columns = [

    0 => 'c.id',

    1 => 'c.course_name',

    2 => 'c.avail_seats'
];

$orderColumn = $columns[$orderColumnIndex];

$response = $courseObj->getCourseByInstructorServerSide(

    $draw,

    $instructor_id,

    $start,

    $length,

    $search,

    $orderColumn,

    $orderDirection
);

echo json_encode($response);
