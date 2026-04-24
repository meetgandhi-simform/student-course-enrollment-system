<?php

session_start();

require_once __DIR__ . "/../helper/auth.php";
requireRole('admin');

require_once __DIR__ . "/../Class/User.php";
require_once __DIR__ . "/../Class/Course.php";
require_once __DIR__ . "./../Class/Enrollments.php";

$userObj = new User();
$courseObj = new Course();
$enrollObj = new Enrollments();

// Using your updated function
$students = $userObj->getStudents();
$courses = $courseObj->getOptionCourses();

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
            <p style="color:red;">
                <?= $_SESSION['error'];
                unset($_SESSION['error']); ?>
            </p>
        <?php endif; ?>

        <form method="POST" action="/course-management/handlers/enrollStudent.php">

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
            <select id="course_id" onchange="findInstructors()" required>
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

        <script>
            function findInstructors() {
                const courseId = document.getElementById("course_id").value;

                if (!courseId) {
                    alert("Please select a course first");
                    return;
                }

                fetch(`/course-management/handlers/enrollStudent.php?course_id=${courseId}`)
                    .then(res => res.json())
                    .then(data => {
                        const dropdown = document.getElementById("instructor_dropdown");
                        dropdown.innerHTML = '<option value="">Select Instructor</option>';
                        data.forEach(row => {
                            const option = document.createElement("option");
                            option.value = row.id;
                            option.text = row.id + " - " + row.name;
                            dropdown.appendChild(option);
                        });
                    })
                    .catch(err => {
                        console.error(err);
                        alert("Something went wrong");
                    });
            }
        </script>

</body>

</html>