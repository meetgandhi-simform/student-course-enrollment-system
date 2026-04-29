<?php
require_once __DIR__ . "./../../class/User.php";
require_once __DIR__ . "./../../class/Instructor.php";
require_once __DIR__ . "./../../class/Course.php";
require_once __DIR__ . "./../../handlers/adminHandlers/assignInstructorHandler.php";

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
    <link rel="stylesheet" href="./../css/index.css">
</head>

<?php require 'navbar.php'; ?>

<body>

    <div class="form-container">
        <h2>Assign Instructor</h2>

        <form method="POST" action="/course-management/handlers/adminHandlers/assignInstructorHandler.php">

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
    </div>

</body>

</html>