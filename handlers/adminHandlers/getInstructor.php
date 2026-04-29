<?php

require_once __DIR__ . "/../../class/Enrollments.php";

if (!isset($_GET['course_id']) || !is_numeric($_GET['course_id'])) {
    echo json_encode(["status" => false, "data" => []]);
    exit();
}

$course_id = (int) $_GET['course_id'];

$enrollObj = new Enrollments();
$result = $enrollObj->getInstructorPerCourse($course_id);

header("Content-Type: application/json");

if ($result['status']) {
    echo json_encode([
        "status" => true,
        "data" => $result['data']
    ]);
} else {
    echo json_encode([
        "status" => false,
        "data" => []
    ]);
}
?>