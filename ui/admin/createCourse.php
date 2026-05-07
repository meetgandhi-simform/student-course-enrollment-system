<?php
require_once __DIR__ . "./../../handlers/adminHandlers/createCourseHandler.php";
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Create Course</title>
    <link rel="stylesheet" href="./../css/index.css">
</head>

<?php require 'navbar.php'; ?>

<body>

    <div class="form-container">
        <h2>Create Course</h2>

        <form method="POST" action="/course-management/handlers/adminHandlers/createCourseHandler.php">

            <label for="name">Course Name</label>
            <input type="text" name="name" id="name" required>

            <label for="weeks">Duration (Weeks)</label>
            <input type="number" name="weeks" id="weeks" required>

            <label for="seats">Seats</label>
            <input type="number" name="seats" id="seats" required>

            <button type="submit">Create Course</button>
        </form>
    </div>
</body>

</html>