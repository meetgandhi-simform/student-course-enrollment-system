<?php
require __DIR__ . "./../../handlers/studentHandlers/studentDashboardHandler.php";
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="./css/index.css">
    <title>All Courses</title>
</head>

<body>
    <?php require_once 'navbar.php' ?>

    <?php if (isset($_SESSION['error'])): ?>
        <p class="error"><?= htmlspecialchars($_SESSION['error']) ?></p>
        <?php unset($_SESSION['error']); ?>
    <?php endif; ?>

    <div class="table-container">
        <h2>All Courses</h2>

        <table class="custom-table">
            <tr>
                <th>Course Id</th>
                <th>Course Name</th>
                <th>Weeks</th>
                <th>Available Seats</th>
                <th>Instructor Name</th>
                <th>Action</th>
            </tr>

            <?php foreach ($courses as $course): ?>
                <tr>
                    <td><?= $course['id']; ?></td>
                    <td><?= $course['course_name']; ?></td>
                    <td><?= $course['duration_weeks']; ?></td>
                    <td><?= $course['avail_seats']; ?></td>
                    <td><?= $course['instructor_name'] ?></td>
                    <td>
                        <a class="active-btn"
                            href="/course-management/handlers/studentHandlers/enrollHandler.php?course_instructor_id=<?= $course['course_instructor_id'] ?>&course_id=<?= $course['id'] ?>">
                            Enroll
                        </a>
                    </td>
                </tr>
            <?php endforeach; ?>
        </table>

        <div class="pagination">
            <?php if ($course_page > 1): ?>
                <a href="?tab=courses&course_page=<?= $course_page - 1 ?>">⬅</a>
            <?php endif; ?>

            <?php for ($i = 1; $i <= $totalCoursePages; $i++): ?>
                <a href="?tab=courses&course_page=<?= $i ?>"
                    class="<?= ($i == $course_page) ? 'active' : '' ?>">
                    <?= $i ?>
                </a>
            <?php endfor; ?>

            <?php if ($course_page < $totalCoursePages): ?>
                <a href="?tab=courses&course_page=<?= $course_page + 1 ?>">➡</a>
            <?php endif; ?>
        </div>
    </div>
</body>
<br /><br />

</html>