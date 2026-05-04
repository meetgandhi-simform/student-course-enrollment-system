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
    <title>Assign Instructor</title>
    <link rel="stylesheet" href="./css/style.css">
</head>

<body>

    <?php require 'navbar.php'; ?>

    <div class="form-container">
        <h2>Assign Instructor</h2>

        <form method="POST" action="/course-management/handlers/assignInstructorHandler.php">

            <label for="course">Course</label>
            <select name="course_id" id="course" required>
                <option value="">Select Course</option>
                <?php foreach ($courses as $course): ?>
                    <option value="<?= $course['id'] ?>">
                        <?= $course['id'] ?> -
                        <?= $course['course_name'] ?>
                    </option>
                <?php endforeach; ?>
            </select>

            <label for="instructor">Instructor</label>
            <select name="instructor_id" id="instructor" required>
                <option value="">Select Instructor</option>
                <?php foreach ($instructors as $inst): ?>
                    <option value="<?= $inst['id'] ?>">
                        <?= $inst['id'] ?> -
                        <?= $inst['name'] ?>
                    </option>
                <?php endforeach; ?>
            </select>

            <button type="submit">Assign</button>
        </form>

        <a href="admin_dashboard.php" class="back-link">← Go To Dashboard</a>
    </div>

</body>

</html>