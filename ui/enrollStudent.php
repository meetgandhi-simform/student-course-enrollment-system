<?php
require_once __DIR__ . "./../Class/User.php";
require_once __DIR__ . "./../Class/Instructor.php";
require_once __DIR__ . "./../Class/Course.php";
require_once __DIR__ . "./../handlers/assignInstructorHandler.php";

$user = new User();
$instructor = new Instructor();
$course = new Course();
$instructors = $instructor->getInstructors();
$courses = $course->getCourses();
?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Enroll Student To Course</title>
</head>
<body>
    <?php require 'navbar.php'; ?>
    <center><h1>Work is Going On</h1></center>
</body>

</html>