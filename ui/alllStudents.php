<?php
require "/var/www/html/course-management/handlers/adminDashboardHandler.php";
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="./css/index.css">
    <title>All students</title>
</head>

<body>
    <?php require 'navbar.php'; ?>

    <?php if (isset($_SESSION['error'])): ?>
        <p class="error"><?= htmlspecialchars($_SESSION['error']) ?></p>
        <?php unset($_SESSION['error']); ?>
    <?php endif; ?>

    <div class="table-container">
        <h2>All Students</h2>

        <table class="custom-table">
            <tr>
                <th>Id</th>
                <th>Name</th>
                <th>Email</th>
                <th>Phone</th>
                <th>Role</th>
                <th>Status</th>
                <th>Action</th>
            </tr>

            <?php foreach ($students as $student): ?>
                <tr>
                    <td><?= $student['id']; ?></td>
                    <td><?= $student['name']; ?></td>
                    <td><?= $student['email']; ?></td>
                    <td><?= $student['phone']; ?></td>
                    <td><?= $student['role']; ?></td>
                    <td>
                        <span class="status <?= strtolower($student['isActive']) ?>">
                            <?= $student['isActive']; ?>
                        </span>
                    </td>
                    <td>
                        <a class="delete-btn"
                            href="/course-management/auth/Delete.php?id=<?= $student['id']; ?>"
                            onclick="return confirm('Are you sure?')">
                            Delete
                        </a>

                        <?php if (strtolower($student['isActive']) === 'active'): ?>
                            <button class="active-btn disabled" disabled>
                                Already Active
                            </button>
                        <?php else: ?>

                            <a class="active-btn"
                                href="/course-management/handlers/activeUserHandler.php?id=<?= $student['id']; ?>"
                                onclick="return confirm('Are You sure You want to activate this user? ')">Activate User
                            </a>
                        <?php endif; ?>

                    </td>
                </tr>
            <?php endforeach; ?>
        </table>

        <!-- Pagination -->
        <div class="pagination">
            <?php if ($student_page > 1): ?>
                <a href="?tab=students&student_page=<?= $student_page - 1 ?>">⬅</a>
            <?php endif; ?>

            <?php for ($i = 1; $i <= $totalStudentPages; $i++): ?>
                <a href="?tab=students&student_page=<?= $i ?>"
                    class="<?= ($i == $student_page) ? 'active' : '' ?>">
                    <?= $i ?>
                </a>
            <?php endfor; ?>

            <?php if ($student_page < $totalStudentPages): ?>
                <a href="?tab=students&student_page=<?= $student_page + 1 ?>">➡</a>
            <?php endif; ?>
        </div>

        <a href="./admin_dashboard.php" class="back-link">← Go To Dashboard</a>
    </div>
</body>
<br /><br />

</html>