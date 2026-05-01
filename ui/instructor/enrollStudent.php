<?php
session_start();

require_once __DIR__ . './../../helper/AuthHelper.php';
AuthHelper::requireRole('instructor');

require_once __DIR__ . '/../../class/User.php';
require_once __DIR__ . '/../../class/Course.php';
require_once __DIR__ . '/../../class/Enrollments.php';

$userObj = new User();
$courseObj = new Course();
$enrollObj = new Enrollments();

$students = $userObj->getStudents();
$courses = $courseObj->getOptionCourseByInstructor($_SESSION['user_id']);
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="/ui/css/index.css">
    <title>Enroll Student</title>
</head>

<body>
    <?php require_once 'navbar.php' ?>

    <div class="form-container">
        <h2>Enroll Student</h2>

        <!-- Messages -->
        <?php if (isset($_SESSION['error'])): ?>
            <p class="error">
                <?= $_SESSION['error'];
                unset($_SESSION['error']); ?>
            </p>
        <?php endif; ?>

        <form method="POST" action="/course-management/handlers/instructorHandlers/enrollStudent.php">

            <!-- Student Dropdown -->
            <label for="student">Student:</label>
            <select name="student_id" id="student" required>
                <option value="">Select Student</option>
                <?php if ($students['status']): ?>
                    <?php foreach ($students['data'] as $student): ?>
                        <option value="<?= $student['id']; ?>">
                            <?= $student['id'] . " - " . $student['name']; ?>
                        </option>
                    <?php endforeach; ?>
                <?php endif; ?>
            </select>

            <br><br>

            <!-- Course Dropdown -->
            <label for="course_id">Course:</label>
            <select id="course_id" name="course_id" onchange="findInstructors()" required>
                <option value="">Select Course</option>
                <?php if ($courses['status']): ?>
                    <?php foreach ($courses['data'] as $course): ?>
                        <option value="<?= $course['id']; ?>">
                            <?= $course['id'] . " - " . $course['course_name']; ?>
                        </option>
                    <?php endforeach; ?>
                <?php endif; ?>
            </select>

            <br><br>

            <!-- Instructor Dropdown -->
            <label for="instructor_dropdown">Instructor:</label>
            <select name="course_instructor_id" id="instructor_dropdown" required>
                <option value="">Select Instructor</option>
            </select>

            <br><br>

            <button type="submit">Enroll</button>

        </form>

        <script src="./../../ui/js/enrollStudent.js"></script>
</body>

</html>