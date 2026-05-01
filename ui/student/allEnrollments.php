<?php
require __DIR__ . "./../../handlers/studentHandlers/studentDashboardHandler.php";
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="./css/index.css">
    <title>All Enrollments</title>
</head>

<body>
    <?php require_once 'navbar.php' ?>

    <?php if (isset($_SESSION['error'])): ?>
        <p class="error"><?= htmlspecialchars($_SESSION['error']) ?></p>
        <?php unset($_SESSION['error']); ?>
    <?php endif; ?>

    <div class="table-container">
        <h2>All Enrollments</h2>

        <table class="custom-table">
            <tr>
                <th>Student Id</th>
                <th>Course Name</th>
                <th>Weeks</th>
                <th>Instructor Id</th>
                <th>Instructor Name</th>
                <th>status</th>
                <th>Action</th>

            </tr>

            <?php foreach ($enrollments as $enrollment): ?>
                <tr>
                    <td><?= $enrollment['id']; ?></td>
                    <td><?= $enrollment['name']; ?></td>
                    <td><?= $enrollment['weeks']; ?></td>
                    <td><?= $enrollment['instructor_id']; ?></td>
                    <td><?= $enrollment['instructor_name'] ?></td>
                    <td>
                        <span class="status <?= strtolower($enrollment['status']) ?>">
                            <?= $enrollment['status']; ?>
                        </span>
                    </td>
                    <td>
                        <?php if ($enrollment['status'] === 'Enrolled'): ?>
                            <a class="delete-btn"
                                href="/course-management/handlers/adminHandlers/deleteEnrollment.php?id=<?= $enrollment['enrollment_id']; ?>"
                                onclick="return confirm('Are you sure you want to delete this Enrollment?')">
                                Cancel
                            </a>
                            <a class="active-btn"
                                href="/course-management/handlers/adminHandlers/completeEnrollment.php?id=<?= $enrollment['enrollment_id']; ?>"
                                onclick="return confirm('Are You sure You want to activate this Enrollment? ')">Complete
                            </a>
                        <?php endif; ?>

                        <?php if ($enrollment['status'] === 'Cancelled'): ?>
                            <a class="active-btn"
                                href="/course-management/handlers/adminHandlers/activeEnrollment.php?id=<?= $enrollment['enrollment_id']; ?>"
                                onclick="return confirm('Are You sure You want to activate this Enrollment? ')">Re-Enroll
                            </a>
                        <?php endif; ?>
                    </td>
                </tr>
            <?php endforeach; ?>
        </table>

        <!-- Pagination -->
        <div class="pagination">
            <?php if ($enroll_page > 1): ?>
                <a href="?tab=enrollments&enroll_page=<?= $enroll_page - 1 ?>">⬅</a>
            <?php endif; ?>

            <?php for ($i = 1; $i <= $totalEnrollmentPages; $i++): ?>
                <a href="?tab=enrollments&enroll_page=<?= $i ?>"
                    class="<?= ($i == $enroll_page) ? 'active' : '' ?>">
                    <?= $i ?>
                </a>
            <?php endfor; ?>

            <?php if ($enroll_page < $totalEnrollmentPages): ?>
                <a href="?tab=enrollments&enroll_page=<?= $enroll_page + 1 ?>">➡</a>
            <?php endif; ?>
        </div>
    </div>
</body>
<br /><br />

</html>