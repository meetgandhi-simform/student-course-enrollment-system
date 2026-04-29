<?php
require_once __DIR__ . "./../../handlers/adminHandlers/adminDashboardHandler.php";
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="./../css/index.css">
    <title>All Admins</title>
</head>

<?php require 'navbar.php'; ?>

<body>
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

                        <?php if ($_SESSION['user_id'] == $admin['id']): ?>

                            <!-- Current Logged-in User -->
                            <span class="disabled-text">Current User</span>

                        <?php else: ?>
                            <?php if ($admin['isActive'] === 'Active') : ?>
                                <!-- Delete Button -->
                                <a class="delete-btn"
                                    href="/course-management/auth/Delete.php?id=<?= $admin['id']; ?>"
                                    onclick="return confirm('Are you sure you want to delete this user?')">
                                    Delete
                                </a>
                            <?php endif; ?>

                            <!-- Active / Activate Button -->
                            <?php if ($admin['isActive'] === 'Inactive'): ?>

                                <a class="active-btn"
                                    href="/course-management/handlers/adminInstructorHandlers/activeUserHandler.php?id=<?= $admin['id']; ?>"
                                    onclick="return confirm('Are you sure you want to activate this user?')">
                                    Activate User
                                </a>

                            <?php endif; ?>

                        <?php endif; ?>

                    </td>
                </tr>
            <?php endforeach; ?>
        </table>

        <!-- Pagination -->
        <div class="pagination">

            <?php if ($admin_page > 1): ?>
                <a href="?tab=admins&admin_page=<?= $admin_page - 1 ?>">⬅</a>
            <?php endif; ?>

            <?php for ($i = 1; $i <= $totalAdminPages; $i++): ?>
                <a href="?tab=admins&admin_page=<?= $i ?>"
                    class="<?= ($i == $admin_page) ? 'active' : '' ?>">
                    <?= $i ?>
                </a>
            <?php endfor; ?>

            <?php if ($admin_page < $totalAdminPages): ?>
                <a href="?tab=admins&admin_page=<?= $admin_page + 1 ?>">➡</a>
            <?php endif; ?>

        </div>
    </div>
</body>

</html>