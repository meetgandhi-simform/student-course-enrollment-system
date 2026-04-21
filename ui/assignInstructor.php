<?php
require_once "/var/www/html/course-management/Class/User.php";
require_once "/var/www/html/course-management/Class/Instructor.php";
require_once "/var/www/html/course-management/Class/Course.php";

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

            <label>Instructor</label>
            <select name="instructor_id" required>
                <option value="">Select Instructor</option>
                <?php foreach ($instructors as $inst): ?>
                    <option value="<?= $inst['id'] ?>">
                        <?=  $inst['id'] ?> -
                        <?= $inst['name'] ?>
                    </option>
                <?php endforeach; ?>
            </select>

            <label>Course</label>
            <select name="course_id" required>
                <option value="">Select Course</option>
                <?php foreach ($courses as $course): ?>
                    <option value="<?= $course['id'] ?>">
                        <?=  $course['id'] ?> -
                        <?= $course['course_name'] ?>
                    </option>
                <?php endforeach; ?>
            </select>

            <button type="submit">Assign</button>
        </form>

        <a href="admin_dashboard.php" class="back-link">← Go To Dashboard</a>
    </div>

</body>

</html>