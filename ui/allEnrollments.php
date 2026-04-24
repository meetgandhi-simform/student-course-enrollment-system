<?php
require_once __DIR__ . "./../handlers/adminDashboardHandler.php";
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>All Enrollments</title>
</head>

<body>
    <?php require 'navbar.php'; ?>

    <?php if (isset($_SESSION['error'])): ?>
        <p class="error"><?= htmlspecialchars($_SESSION['error']) ?></p>
        <?php unset($_SESSION['error']); ?>
    <?php endif; ?>

    <div class="table-container">
        <h2>All Enrollments</h2>

        <table class="custom-table">
            <tr>
                <th>Id</th>
                <th>Course Name</th>
                <th>Student Id</th>
                <th>Student Name</th>
                <th>Instructor Id</th>
                <th>Instructor Name</th>
                <th>Status</th>
                <th>Action</th>
            </tr>
            <?php foreach ($enrollments as $enrollment): ?>
                <tr>
                    <td><?= $enrollment['enrollment_id']; ?></td>
                    <td><?= $enrollment['course_name']; ?></td>
                    <td><?= $enrollment['student_id']; ?></td>
                    <td><?= $enrollment['student_name']; ?></td>
                    <td><?= $enrollment['instructor_id']; ?></td>
                    <td><?= $enrollment['instructor_name']; ?></td>
                    <td>
                        <span class="status <?= strtolower($enrollment['enrollment_status']) ?>">
                            <?= $enrollment['enrollment_status']; ?>
                        </span>
                    </td>
                    <td>
                        <?php if ($enrollment['enrollment_status'] === 'Enrolled'): ?>
                            <a class="delete-btn"
                                href="/course-management/handlers/deleteEnrollment.php?id=<?= $enrollment['enrollment_id']; ?>"
                                onclick="return confirm('Are you sure you want to delete this Enrollment?')">
                                Cancel Enrollment
                            </a>
                        <?php endif; ?>

                        <?php if ($enrollment['enrollment_status'] === 'Cancelled'): ?>
                            <a class="active-btn"
                                href="/course-management/handlers/activeEnrollment.php?id=<?= $enrollment['enrollment_id']; ?>"
                                onclick="return confirm('Are You sure You want to activate this Enrollment? ')">Re-Enroll
                            </a>
                        <?php endif; ?>
                    </td>
                </tr>
            <?php endforeach; ?>
        </table>

        <!-- Pagination -->
        <div class="pagination">

            <!-- Previous Button -->
            <?php if ($enrollment_page > 1): ?>
                <a href="?enrollment_page=<?= $enrollment_page - 1 ?>">⬅</a>
            <?php endif; ?>

            <!-- Page Numbers -->
            <?php for ($i = 1; $i <= $totalEnrollmentPages; $i++): ?>
                <a href="?enrollment_page=<?= $i ?>"
                    class="<?= ($i == $enrollment_page) ? 'active' : '' ?>">
                    <?= $i ?>
                </a>
            <?php endfor; ?>

            <!-- Next Button -->
            <?php if ($enrollment_page < $totalEnrollmentPages): ?>
                <a href="?enrollment_page=<?= $enrollment_page + 1 ?>">➡</a>
            <?php endif; ?>

        </div>

        <a href="./admin_dashboard.php" class="back-link">← Go To Dashboard</a>
    </div>
</body>

</html>