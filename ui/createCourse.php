<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Create Course</title>
    <link rel="stylesheet" href="./css/style.css"> <!-- your existing CSS -->
</head>

<body>

    <?php require 'navbar.php'; ?>

    <div class="form-container">
        <h2>Create Course</h2>

        <form method="POST" action="/course-management/handlers/createCourseHandler.php">

            <label for="name">Course Name</label>
            <input type="text" name="name" id="name" required>

            <label for="weeks">Duration (Weeks)</label>
            <input type="number" name="weeks" id="weeks" required>

            <label for="seats">Seats</label>
            <input type="number" name="seats" id="seats" required>

            <button type="submit">Create Course</button>
        </form>

        <a href="admin_dashboard.php" class="back-link">← Go To Dashboard</a>
    </div>

</body>

</html>