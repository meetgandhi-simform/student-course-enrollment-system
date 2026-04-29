<?php
require __DIR__ . "./../../handlers/adminHandlers/adminDashboardHandler.php";
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="./../css/index.css">
    <title>Course With Instructor</title>
</head>

<?php require 'navbar.php'; ?>

<body>
    <?php if (isset($_SESSION['error'])): ?>
        <p class="error"><?= htmlspecialchars($_SESSION['error']) ?></p>
        <?php unset($_SESSION['error']); ?>
    <?php endif; ?>

    <div class="table-container">
        <h2>Course With Instructor</h2>

        <table class="custom-table">
            <tr>
                <th>Id</th>
                <th>Course Name</th>
                <th>Seats Left</th>
                <th>Instructor Id</th>
                <th>Instructor Name</th>
                <th>Status</th>
                <th>Action</th>
            </tr>

            <?php if (empty($courseWithInstructors)): ?>
                <tr>
                    <td colspan="7" style="text-align:center;">No courses found</td>
                </tr>
            <?php else: ?>
                <?php foreach ($courseWithInstructors as $course): ?>
                    <tr>
                        <td><?= $course['id']; ?></td>
                        <td><?= $course['course_name']; ?></td>
                        <td><?= $course['avail_seats'] ?> / <?= $course['max_seats']; ?></td>
                        <td><?= $course['instructor_id']; ?></td>
                        <td><?= $course['name']; ?></td>
                        <td>
                            <span class="status <?= strtolower($course['isActive']) ?>">
                                <?= $course['isActive']; ?>
                            </span>
                        </td>
                        <td>
                            <a class="delete-btn"
                                href="/course-management/handlers/adminHandlers/deleteCourseInstructor.php?course_id=<?= $course['id']; ?>&instructor_id=<?= $course['instructor_id']; ?>"
                                onclick="return confirm('Remove instructor from this course?')">
                                Remove Instructor
                            </a>
                        </td>
                    </tr>
                <?php endforeach; ?>
            <?php endif; ?>
        </table>

        <!-- Pagination -->
        <div class="pagination">

            <?php if ($course_page > 1): ?>
                <a href="?tab=courses&course_page=<?= $course_page - 1 ?>">⬅</a>
            <?php endif; ?>

            <?php for ($i = 1; $i <= $totalCourseWithInstructorPages; $i++): ?>
                <a href="?tab=courses&course_page=<?= $i ?>"
                    class="<?= ($i == $course_page) ? 'active' : '' ?>">
                    <?= $i ?>
                </a>
            <?php endfor; ?>

            <?php if ($course_page < $totalCourseWithInstructorPages): ?>
                <a href="?tab=courses&course_page=<?= $course_page + 1 ?>">➡</a>
            <?php endif; ?>

        </div>
    </div>
</body>

</html>