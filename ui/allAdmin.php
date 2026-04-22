<?php
require_once __DIR__ . "./../handlers/adminDashboardHandler.php";
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>All Admins</title>
</head>

<body>
    <?php require 'navbar.php'; ?>

    <?php if (isset($_SESSION['error'])): ?>
        <p class="error"><?= htmlspecialchars($_SESSION['error']) ?></p>
        <?php unset($_SESSION['error']); ?>
    <?php endif; ?>

    <div class="table-container">
        <h2>All Admins</h2>

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
            <?php foreach ($admins as $admin): ?>
                <tr>
                    <td><?= $admin['id']; ?></td>
                    <td><?= $admin['name']; ?></td>
                    <td><?= $admin['email']; ?></td>
                    <td><?= $admin['phone']; ?></td>
                    <td><?= $admin['role']; ?></td>
                    <td>
                        <span class="status <?= strtolower($admin['isActive']) ?>">
                            <?= $admin['isActive']; ?>
                        </span>
                    </td>
                    <td>
                        <a class="delete-btn"
                            href="/course-management/auth/Delete.php?id=<?= $admin['id']; ?>"
                            onclick="return confirm('Are you sure you want to delete this user?')">
                            Delete
                        </a>

                        <?php if (strtolower($admin['isActive']) === 'active'): ?>
                            <button class="active-btn disabled" disabled>
                                Already Active
                            </button>
                        <?php else: ?>

                            <a class="active-btn"
                                href="/course-management/handlers/activeUserHandler.php?id=<?= $admin['id']; ?>"
                                onclick="return confirm('Are You sure You want to activate this user? ')">Activate User
                            </a>
                        <?php endif; ?>
                    </td>
                </tr>
            <?php endforeach; ?>
        </table>

        <!-- Pagination -->
        <div class="pagination">

            <?php if ($admin_page > 1): ?>
                <a href="?admin_page=<?= $admin_page - 1 ?>">⬅</a>
            <?php endif; ?>

            <?php for ($i = 1; $i <= $totalAdminPages; $i++): ?>
                <a href="?admin_page=<?= $i ?>"
                    class="<?= ($i == $admin_page) ? 'active' : '' ?>">
                    <?= $i ?>
                </a>
            <?php endfor; ?>

            <?php if ($admin_page < $totalAdminPages): ?>
                <a href="?admin_page=<?= $admin_page + 1 ?>">➡</a>
            <?php endif; ?>

        </div>

        <a href="./admin_dashboard.php" class="back-link">← Go To Dashboard</a>
    </div>
</body>

</html>